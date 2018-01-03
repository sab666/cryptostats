<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 03-01-18
 */

namespace App\Entity;


class BitcoinDiamond extends Coin implements CoinInterface
{
    public function __construct(array $data = [])
    {
        $this->setName('Bitcoin Diamond');
        $this->setSymbol('BTD');

        parent::__construct($data);
    }

    public function getAmount()
    {
        if ($this->amount != null) return $this->amount;

        $total = 0;
        foreach ($this->addresses as $address) {
            // TODO: implement getAmount for BTD
        }

        return parent::getAmount();
    }
}
