<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:03
 */

namespace App\Entity;


class Bitcoin extends Coin implements CoinInterface
{

    public function __construct(array $data = [])
    {
        $this->setName('Bitcoin');
        $this->setSymbol('BTC');

        parent::__construct($data);
    }

    public function getAmount()
    {
        if ($this->amount != null) return $this->amount;

        $total = 0;
        foreach ($this->addresses as $address) {
            $balance = file_get_contents(sprintf(
                'https://blockchain.info/de/q/addressbalance/%s?confirmations=3',
                $address
            ));

            if (is_numeric($balance)) {
                $total += $this->satoshiToBTC($balance);
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

    private function satoshiToBTC($satoshi) {
        return bcdiv($satoshi,100000000,8);
    }
}