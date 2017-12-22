<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 16:03
 */

namespace App\Entity;


class BitcoinCash extends Coin implements CoinInterface
{

    public function __construct(array $data = [])
    {
        $this->setName('Bitcoin Cash');
        $this->setSymbol('BCH');

        parent::__construct($data);
    }

    public function getAmount()
    {
        if ($this->amount != null) return $this->amount;

        $total = 0;
        foreach ($this->addresses as $address) {
            $balance = file_get_contents(sprintf(
                'https://cashexplorer.bitcoin.com/api/addr/%s/balance',
                $address));

            if (is_numeric($balance)) {
                $total += $this->satoshiToBCH($balance);
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

    private function satoshiToBCH($satoshi) {
        return bcdiv($satoshi,100000000,8);
    }
}