<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:03
 */

namespace App\Entity;


class Ripple extends Coin
{
    public function __construct(array $data = [])
    {
        $this->setName('Ripple');
        $this->setSymbol('XRP');

        parent::__construct($data);
    }
}