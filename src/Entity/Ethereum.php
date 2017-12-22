<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:03
 */

namespace App\Entity;


class Ethereum extends Coin implements CoinInterface
{
    public function __construct(array $data = [])
    {
        $this->setName('Ethereum');
        $this->setSymbol('ETH');

        parent::__construct($data);
    }

    public function getAmount()
    {
        if ($this->amount != null) return $this->amount;

        $total = 0;
        foreach ($this->addresses as $address) {
            $json = json_decode(file_get_contents('https://api.etherscan.io/api?module=account&action=balance&address=' . $address . '&tag=latest'), true);
            if (is_numeric($json['result'])) {
                $total += $this->weiToEth($json['result']);
            } else {
                $total = null;
                break;
            }
        }

        if ($total != null) {
            $this->amount = $total;
        }

        return parent::getAmount();
    }

    private function weiToEth($wei)
    {
        return bcdiv($wei, 1000000000000000000, 18);
    }
}