<?php

require_once __DIR__ . "/cart/User.php";
require_once __DIR__ . "/cart/Cart.php";


/** *************** Distribute smoothies ***************** */
/**
 * Distribute smoothies among hipsters
 * @param int $m // колличество смузи
 * @param int $n // колличество хипстеров
 * @return int // колличество смузи, доставшееся каждому хипстеру
 */
function distributeSmoothies(int $m, int $n): int
{
    return floor($m/$n);
}

echo PHP_EOL . "Distribute smoothies tests:". PHP_EOL;
assertSame(0, distributeSmoothies(4, 5));
assertSame(1, distributeSmoothies(5, 5));
assertSame(1, distributeSmoothies(7, 5));
assertSame(3, distributeSmoothies(16, 5));


/** *************** Maximal amount ***************** */
/**
 * @param int      $m1
 * @param int      $p1
 * @param int      $m2
 * @param int      $p2
 * @param int      $mMax
 * @param int|null $n1
 * @param int|null $n2
 * @return int
 */
function maximalAmount(int $m1, int $p1, int $m2, int $p2, int $mMax, int $n1 = null, int $n2 = null): int
{
    $pArr = [];
    $n1 = $n1 ?: floor($mMax / $m1);
    $n2 = $n2 ?: floor($mMax / $m2);
    for ($i = 0; $i <= $n1; $i++) {
        for ($j = 0; $j <= $n2; $j++) {
            if ($mMax >= ($m1 * $i + $m2 * $j))
                $pArr[] = $p1 * $i + $p2 * $j;
        }
    }
    return max($pArr);
}

echo PHP_EOL . "Maximal cost:". PHP_EOL;
assertSame(11, $actual = maximalAmount(2, 5, 3, 6, 5), "Maximal amount: $actual");
assertSame(30, $actual = maximalAmount(4, 20, 1, 6, 5), "Maximal amount: $actual");
assertSame(29, $actual = maximalAmount(4, 24, 1, 5, 5, 1, 1), "Maximal amount: $actual");


/** *************** Bad clown ***************** */
/**
 * Correct smiles.
 * @param string $text
 * @return string
 */
function correctSmiles(string $text): string
{
    $pattern = '/(:[()])([()]+)/';
    $replace = '$1';
    return preg_replace($pattern, $replace, $text);
}

echo PHP_EOL . "Bad clown tests:". PHP_EOL;
assertSame(":) Hello:(people :) Hello:", correctSmiles(":) Hello:())(people :)) Hello:"));
assertSame(":) Hello:(people :) Hello:", correctSmiles(":) Hello:())(people :)) Hello:"));
assertSame("()((:)", correctSmiles("()((:)))"));


/** *************** Discount ***************** */
echo PHP_EOL . "Discount:" . PHP_EOL;
$testData = [
    "NoDiscounts" => [
        "age" => 18,
        "firstPurchase" => false,
        "amount" => 7000,
        "items" => [
            ["id" => 1, "price" => 2000],
            ["id" => 2, "price" => 2000],
            ["id" => 3, "price" => 3000],
        ],
        "discountedTotalAmount" => 7000,
        "discountedItems" => [
            ["id" => 1, "price" => 2000],
            ["id" => 2, "price" => 2000],
            ["id" => 3, "price" => 3000],
        ],
    ],
    "DiscountByFirstPurchase" => [
        "age" => 18,
        "firstPurchase" => true,
        "amount" => 7000,
        "items" => [
            ["id" => 1, "price" => 2000],
            ["id" => 2, "price" => 2000],
            ["id" => 3, "price" => 3000],
        ],
        "discountedTotalAmount" => 6900,
        "discountedItems" => [
            ["id" => 1, "price" => 1971],
            ["id" => 2, "price" => 1971],
            ["id" => 3, "price" => 2957],
        ],
    ],
    "DiscountByAge" => [
        "age" => 70,
        "firstPurchase" => false,
        "amount" => 7000,
        "items" => [
            ["id" => 1, "price" => 2000],
            ["id" => 2, "price" => 2000],
            ["id" => 3, "price" => 3000],
        ],
        "discountedTotalAmount" => 6650,
        "discountedItems" => [
            ["id" => 1, "price" => 1900],
            ["id" => 2, "price" => 1900],
            ["id" => 3, "price" => 2850],
        ],
    ],
    "AllDiscounts" => [
        "age" => 70,
        "firstPurchase" => true,
        "amount" => 12000,
        "items" => [
            ["id" => 1, "price" => 3000],
            ["id" => 2, "price" => 4000],
            ["id" => 3, "price" => 5000],
        ],
        "discountedTotalAmount" => 10805,
        "discountedItems" => [
            ["id" => 1, "price" => 2826],
            ["id" => 2, "price" => 3768],
            ["id" => 3, "price" => 4710],
        ],
    ],
];

foreach ($testData as $test) {
    $age = $test["age"];
    $firstPurchase = $test["firstPurchase"];
    $amount = $test["amount"];
    $items = $test["items"];

    $discountedTotalAmount = $test["discountedTotalAmount"];
    $discountedItems = $test["discountedItems"];

    $user = new User($age, $firstPurchase);
    $cart = new Cart($user);

    foreach ($items as $item) {
        $cart->addItem($item);
    }

    echo "Age: $age, FirstPurchase: " . ($firstPurchase ? "true" : "false") . ", Amount: $amount" . PHP_EOL;
    assertSame($discountedTotalAmount, $actual = $cart->getDiscountedTotalAmount(), "Discounted total amount: $actual");
    foreach ($discountedItems as $item) {
        assertSame($item["price"], $actual = $cart->getDiscountedItemAmount($item["id"]), "Discounted item: $actual");
    }
    echo PHP_EOL;
}



function assertSame($a, $b, string $message = null)
{
    $result = $a === $b ? "OK" : "FAIL";
    $message = $message === null ? "" : " - $message";
    echo $result . $message . PHP_EOL;
}