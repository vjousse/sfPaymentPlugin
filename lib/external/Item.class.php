<?php

class Item implements sfPaymentItemInterface
{
  private $reference;
  private $name;
  private $quantity;
  private $price;

  function __construct() {
    $name = '';
    $quantity = 0;
    $price = 0.0;
  }

  public function getReference() {
    return $this->reference;
  }

  public function getName() {
    return $this->name;
  }

  public function getQuantity() {
    return $this->quantity;
  }

  public function getPrice() {
    return $this->price;
  }

  public function setReference($reference) {
    $this->reference = $reference;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }

  public function setPrice($price) {
    $this->price = $price;
  }

}

?>