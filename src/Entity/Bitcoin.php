<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:03
 */

namespace App\Entity;


class Bitcoin extends Coin
{
    public function __construct()
    {
        $this->setName('Bitcoin');
        $this->setSymbol('BTX');
    }
}