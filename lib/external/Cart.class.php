<?php

class Cart implements sfPaymentCartInterface
{
	private $items;

	private $totalAmount;

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$this->items = array();
		$this->totalAmount = 0;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function getTotalAmount()
	{
		return $this->totalAmount;
	}

	public function addItem(sfPaymentItemInterface $item)
	{
		$this->items[] = $item;
	}

	public function modifyItem($item, $quantity)
	{
	  if($quantity <= 0) return;
	  if($quantity == 0) return $this->deleteItem($item);

	  foreach($this->items as $i => $cartItem)
	  {
	    if($cartItem->getReference() == $item->getReference()) {
	      $this->items[$i] = $item;
	    }
	  }
	}

	public function deleteItem($item)
	{
	  foreach($this->items as $i => $cartItem)
	  {
	    if($cartItem->getReference() == $item->getReference()) {
	      unset($this->items[$i]);
	    }
	  }
	}

	public function size()
	{
		return count($this->items);
	}

}