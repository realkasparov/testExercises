<?php

class Discount
{
    private const BIG_AMOUNT = 10000;
    private const BIG_AMOUNT_DISCOUNT = 500;
    private const RETIRE_AGE = 65;
    private const RETIRE_DISCOUNT = 0.95;
    private const FIRST_PURCHASE = 100;

    public static function calcTotalDiscount(User $user, int $amount, $totalAmount = null): int
    {
        if ($user->isFirstPurchase()) {
            $amount *= self::getDiscountInPercent(
                self::FIRST_PURCHASE,
                $totalAmount === null ? $amount : $totalAmount
            );
        }

        if ($user->getAge() > self::RETIRE_AGE) {
            $amount *=  self::RETIRE_DISCOUNT;
        }

        if ($amount > self::BIG_AMOUNT) {
            $amount *= self::getDiscountInPercent(
                self::BIG_AMOUNT_DISCOUNT,
                $totalAmount === null ? $amount : $totalAmount
            );
        }

        return $amount;
    }

    private static function getDiscountInPercent($discount, $amount): float
    {
        return (100 - $discount / $amount * 100) / 100;
    }
}