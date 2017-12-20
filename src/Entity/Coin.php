<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 11-12-17
 * Time: 15:52
 */

namespace App\Entity;


use Psr\Log\LoggerInterface;

class Coin implements CoinInterface
{
    protected $name = null;
    protected $symbol = null;
    protected $balance = null;
    protected $exchangeRate = null;
    protected $addresses = [];
    protected $fiat = null;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(array $data = [])
    {
        $this->fiat = $data['fiat'];

        if(isset($data['addresses']) && is_array($data['addresses'])) {
            $this->addresses = $data['addresses'];
        }

        if(isset($data['balance']) && is_float($data['balance'])) {
            $this->balance = $data['balance'];
        }
    }

    /** @required */
    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;

        $this->logger->debug('Yaaay, COIN has a logger now!');
    }

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

        $key = sprintf('%s-%s', $this->symbol, $fiatSymbol);

        // Get rate from cryptonator.com if not yet set or if forced.
        if ($forceUpdate || $this->exchangeRate === null) {
            $json = json_decode(@file_get_contents(sprintf(
                'https://api.cryptonator.com/api/ticker/%s',
                $key
            )), true);

            if (is_array($json) && isset($json['ticker']) && isset($json['ticker']['price'])) {
                $this->exchangeRate = $json['ticker']['price'];
            }
        }

        return $this->exchangeRate;
    }

    /**
     * @return array
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    /**
     * @param array $addresses
     */
    public function setAddresses(array $addresses): void
    {
        $this->addresses = $addresses;
    }


}