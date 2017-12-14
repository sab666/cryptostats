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
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CoinsWorthCommand extends ContainerAwareCommand
{
    private $logger;
    /** @var Coin[] */
    private $wallets;

    /**
     * CoinsWorthCommand constructor.
     * @param $logger
     */
    public function __construct(LoggerInterface $logger, WalletCollectionController $walletCollection)
    {
        $this->logger = $logger;
        $this->wallets = $walletCollection->getWallets();

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
        $output->writeln('LEAVE ME BE');
    }


}