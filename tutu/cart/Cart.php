<?php
require_once __DIR__ . "/Discount.php";

class Cart
{
	private $_user;

	private $_items = [];

	public function __construct(?User $user)
	{
		$this->_user = $user;
	}

	public function getUser(): ?User
	{
		return $this->_user;
	}

	// item_id, price, sku, etc.
	public function addItem(array $item): void
	{
		$this->_items[] = $item;
	}

	public function getItemById(int $id): array
    {
        $result = [];
        foreach ($this->_items as $item)
            if ($item["id"] === $id) {
                $result = $item;
                break;
            }
        return !empty($result) ? $result: ["error" => "There is no item with id = $id"];
    }

	public function getTotalAmount(): int
	{
		$ret = 0;
		foreach ($this->_items as $item)
			$ret += $item['price'];
		return $ret;
	}

	public function getDiscountedTotalAmount(): int
    {
        return Discount::calcTotalDiscount($this->_user, $this->getTotalAmount());
    }

    public function getDiscountedItemAmount($id): int
    {
        $itemPrice = $this->getItemById($id)["price"];
        $totalAmount = $this->getTotalAmount();
        return Discount::calcTotalDiscount($this->_user, $itemPrice, $totalAmount);
    }
}