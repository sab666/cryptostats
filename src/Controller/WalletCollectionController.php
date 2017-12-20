<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 13-12-17
 * Time: 20:28
 */

namespace App\Controller;


use App\Entity\CoinFactory;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class WalletCollectionController extends Controller
{
    private $logger;
    private $wallets;
    private $fiat;
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
        $this->fiat = $container->getParameter('fiatCurrency');

        $this->loadWallets($currencies);
    }

    private function loadWallets($currencies)
    {
        if(!is_array($currencies) || count($currencies) < 1) {
            throw new Exception('No currencies defined in config.yml. Sadness.');
        }
        // Assign wallets
        foreach ($currencies as $name=>$data) {
            $this->logger->debug(sprintf(
                "WalletCollection: Adding a '%s' wallet to the Collection.",
                $name
            ));
            $data['fiat'] = $this->fiat;
            $this->wallets[] = CoinFactory::build($name, $data);
        }

    }

    public function loadExchangeRates($fiat)
    {
        foreach($this->getWallets() as $wallet) {
            try {
                $this->logger->info(sprintf(
                    'Loading exchange rate for %s/%s.',
                    $wallet->getName(),
                    $this->fiat
                ));
                $wallet->getExchangerate($this->fiat);
            } catch (Exception $exception) {

            }
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