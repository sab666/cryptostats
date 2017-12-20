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
}