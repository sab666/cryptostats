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
}