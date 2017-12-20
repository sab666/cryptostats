<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 14-12-17
 * Time: 18:30
 */

namespace App\Commands;


use App\Controller\WalletCollectionController;
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

        foreach ($this->walletCollection->getWallets() as $wallet) {
            try {
                $rate = $wallet->getExchangeRate('EUR');
                $output->writeln(sprintf(
                    'Exchange rate for %s is %s',
                    $wallet->getName(),
                    $rate
                ));
            } catch (Exception $exception) {
                $this->logger->critical(sprintf(
                    "Everything went to shit! %s",
                    $exception->getMessage()
                ));
            }
        }
    }


}