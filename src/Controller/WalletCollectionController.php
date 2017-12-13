<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 13-12-17
 * Time: 20:28
 */

namespace App\Controller;


use App\Entity\Coin;
use App\Entity\CoinFactory;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WalletCollectionController extends Controller
{
    private $logger;
    private $wallets;
    protected $container;

    /**
     * WalletCollectionController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->container = $container;
//        $this->wallets = $this->container->parameters;

        $currencies = $container->getParameter('currencies');
        $this->loadWallets($currencies);
    }

    private function loadWallets($currencies)
    {
        if(!is_array($currencies) || count($currencies) < 1) {
            throw new Exception('No currencies defined in config.yml. Sadness.');
        }
        // Assign wallets
        foreach ($currencies as $name=>$data) {
            $this->logger->info(sprintf(
                "Adding a '%s' wallet to the Collection.",
                $name
            ));
            $this->wallets[] = CoinFactory::build($name, $data);
        }

    }

    /**
     * @return array
     */
    public function getWallets()
    {
        return $this->wallets;
    }

}