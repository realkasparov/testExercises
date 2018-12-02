<?php

class User
{
    private $_age;
    private $_firstPurchase;

    public function __construct(int $age, bool $firstPurchase)
    {
        $this->_age = $age;
        $this->_firstPurchase = $firstPurchase;
    }

	public function getAge(): int
    {
		return $this->_age;
	}

	public function isFirstPurchase(): bool
    {
        return $this->_firstPurchase;
    }
}