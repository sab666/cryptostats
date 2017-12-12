<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:32
 */

namespace App\Commands;


use App\Entity\Bitcoin;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCoinsCommand extends Command
{
    private $logger;

    /**
     * ListCoinsCommand constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

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


        $bla = new Bitcoin();
        $bla->getExchangeRate('USD');
        var_dump($bla);

    }
}