<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 14-12-17
 * Time: 18:30
 */

namespace App\Commands;


use App\Controller\WalletCollectionController;
use App\Entity\Coin;
use App\Entity\CoinInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CoinsWorthCommand extends ContainerAwareCommand
{
    private $logger;
    /** @var CoinInterface[] */
    private $walletCollection;

    /**
     * CoinsWorthCommand constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger, WalletCollectionController $walletCollection)
    {
        $this->logger = $logger;
        $this->walletCollection = $walletCollection;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName("coins:worth")
            ->setDescription("Lists Coins, their absolute worth and a grand total");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Getting Total worth');
        $this->walletCollection->loadExchangeRates('EUR');

        $allCoinWorth = $this->walletCollection->getTotalWorth();

        if(isset($allCoinWorth['total'])) {
            $total = $allCoinWorth['total'];
            unset($allCoinWorth['total']);
        } else {
            $total = false;
        }

        foreach($allCoinWorth as $name=>$worth) {
            $output->writeln(sprintf(
                'Balance for %s is %s',
                $name,
                Coin::formatAmount($worth)
            ));
        }

        if($total) {
            $output->writeln(sprintf(
                'Total worth is %s',
                Coin::formatAmount($total)
            ));
        }


    }


}