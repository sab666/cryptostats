<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 13-12-17
 * Time: 20:56
 */

namespace App\Entity;


use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;

class CoinFactory
{

    /**
     * @param $type
     * @param $data
     * @return CoinInterface
     * @throws Exception
     */
    public static function build($type, $data)
    {
        switch ($type) {
            case "bitcoin":
                return new Bitcoin($data);
                break;

            case 'ethereum':
                return new Ethereum($data);
                break;

            case 'ripple':
                return new Ripple($data);
                break;

            case 'bitcoincash':
                return new BitcoinCash($data);
                break;

            case 'nxt':
                return new Nxt($data);
                break;

            default:
                throw new Exception(sprintf(
                    "No entity found for coin type '%s'.",
                    $type
                ));
        }
    }

}