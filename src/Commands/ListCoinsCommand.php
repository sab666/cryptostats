<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:32
 */

namespace App\Commands;


use App\Controller\WalletCollectionController;
use App\Entity\Coin;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCoinsCommand extends ContainerAwareCommand
{
    private $logger;
    /** @var Coin[]  */
    private $wallets;

    /**
     * ListCoinsCommand constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, WalletCollectionController $walletCollection)
    {
        $this->logger = $logger;
        $this->wallets = $walletCollection->getWallets();

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName("coins:list")
            ->setDescription("Lists coins. Obvious, really.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Listing Coins');
        $output->writeln('Huh? What? I was sleeping, leave me alone.');
        foreach($this->wallets as $wallet) {
            $output->writeln(sprintf(
                'Found one %s (%s) wallet.',
                $wallet->getName(),
                $wallet->getSymbol()
            ));
        }



    }
}