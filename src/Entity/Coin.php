<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 15:52
 */

namespace App\Entity;


class Coin
{
    private $name = null;
    private $symbol = null;
    private $balance = null;
    private $exchangeRate = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    protected function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance)
    {
        $this->balance = $balance;
    }

    /**
     * @param string $fiatSymbol
     * @param bool $forceUpdate
     * @return float|null
     * @throws \Exception
     */
    public function getExchangeRate(string $fiatSymbol, bool $forceUpdate = false)
    {
        if(!preg_match('/^[A-Z]{3}$/', $fiatSymbol)) {
            throw new \Exception("Not a valid symbol: '$fiatSymbol'.");
        }

        $key = sprintf('X%sZ%s', $this->symbol, $fiatSymbol);

        // Get rate from Kraken if not yet set or if forced.
        if ($forceUpdate || $this->exchangeRate === null) {
            $json = json_decode(file_get_contents(sprintf(
                'https://api.kraken.com/0/public/Ticker?pair=%s%s',
                $this->symbol,
                $fiatSymbol
            )),true);

            if(is_array($json) && isset($json['result']) && isset($json['result'][$key])) {
                $this->exchangeRate = $json['result'][$key]['c'][0];
            }
            print_r($json);
        }

        return $this->exchangeRate;
    }


}