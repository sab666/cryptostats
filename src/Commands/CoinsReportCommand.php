<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 22-12-17
 * Time: 17:39
 */

namespace App\Commands;


use App\Controller\WalletCollectionController;
use App\Entity\CoinInterface;
use InfluxDB\Client as InfluxDB;
use InfluxDB\Point;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CoinsReportCommand extends ContainerAwareCommand
{
    private $logger;
    /** @var CoinInterface[]  */
    private $wallets;
    private $influxDB;

    /**
     * CoinsReportCommand constructor.
     * @param $logger
     * @param CoinInterface[] $wallets
     */
    public function __construct(LoggerInterface $logger, WalletCollectionController $wallets, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->wallets = $wallets;

        $influxDBSettings = $container->getParameter('influxdb');
        $influxClient = new InfluxDB($influxDBSettings['host'], $influxDBSettings['port']);
        $this->influxDB = $influxClient->selectDB($influxDBSettings['database']);

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName("coins:report")
            ->setDescription("Reports coin worth and exchange rates to influxDB.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        define('CURRENCY', 'EUR');

        $this->wallets->loadExchangeRates(CURRENCY);
        $balances = $this->wallets->getTotalWorth();

        $timestamp = time();
        $exchangeRatesReport = [];
        $balancesReport = [];
        $amountsReport = [];

        foreach($this->wallets->getWallets() as $wallet) {
            $exchangeRate = $wallet->getExchangeRate(CURRENCY);

            if($exchangeRate != null) {
                $exchangeRatesReport[] = new Point(
                    'price',
                    floatval($exchangeRate),
                    [ 'coin' => $wallet->getSymbol() ],
                    [ 'name' => $wallet->getName() ],
                    $timestamp
                );

            }

            $balance = $wallet->getBalance();
            if($balance != null) {
                $balancesReport[] = new Point(
                    'worth',
                    floatval($balance),
                    [ 'coin' => $wallet->getSymbol() ],
                    [ 'name' => $wallet->getName() ],
                    $timestamp
                );
            }

            $coinAmount = $wallet->getAmount();
            if($coinAmount != null && $coinAmount > 0) {
                $amountsReport[] = new Point(
                    'coins',
                    floatval($coinAmount),
                    [ 'coin' => $wallet->getSymbol() ],
                    [ 'name' => $wallet->getName() ],
                    $timestamp
                );
            }
        }

        if(isset($balances['total']) && $balances['total'] != null) {
            $balancesReport[] = new Point(
                'worth',
                $balances['total'],
                [ 'coin' => 'TOTAL' ],
                [ 'name' => 'Total' ],
                $timestamp
            );
        }

        $this->logger->info('Exchange rates'. print_r($exchangeRatesReport, true));
        $this->influxDB->writePoints($exchangeRatesReport, $this->influxDB::PRECISION_SECONDS);

        $this->logger->info('Balances'. print_r($balancesReport, true));
        $this->influxDB->writePoints($balancesReport, $this->influxDB::PRECISION_SECONDS);

        $this->logger->info('Amounts'. print_r($amountsReport, true));
        $this->influxDB->writePoints($amountsReport, $this->influxDB::PRECISION_SECONDS);

    }


}