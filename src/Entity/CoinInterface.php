<?php
/**
 * Created by PhpStorm.
 * User: sab
 * Date: 14-12-17
 * Time: 18:41
 */

namespace App\Entity;

interface CoinInterface
{
    public function __construct(array $data);

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSymbol(): string;

    /**
     * @return string
     */
    public function getBalance();

    /**
     * @param float $balance
     */
    public function setBalance(float $balance);

    /**
     * @param string $fiatSymbol
     * @param bool $forceUpdate
     * @return float|null
     * @throws \Exception
     */
    public function getExchangeRate(string $fiatSymbol, bool $forceUpdate = false);

    /**
     * @return array
     */
    public function getAddresses(): array;

    /**
     * @param array $addresses
     */
    public function setAddresses(array $addresses): void;
}