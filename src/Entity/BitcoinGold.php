<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 24-12-17
 * Time: 10:28
 */

namespace App\Entity;


class BitcoinGold extends Coin implements CoinInterface
{
    public function __construct(array $data = [])
    {
        $this->setName('Bitcoin Gold');
        $this->setSymbol('BTG');

        parent::__construct($data);
    }

    public function getAmount()
    {
        if ($this->amount != null) return $this->amount;

        $total = 0;
        foreach ($this->addresses as $address) {
            $addressInfo = file_get_contents(sprintf(
                'https://btgexp.com/ext/getaddress/%s',
                $address
            ));

            if(!isset($addressInfo['error']) && is_numeric($addressInfo['balance'])) {
                // TODO: implement getAmount for BTG
            }

        }

        return parent::getAmount();
    }
}