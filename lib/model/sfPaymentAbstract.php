<?php

abstract class sfPaymentAbstract extends sfPaymentTransaction {

  /**
   * Enables the test mode
   *
   * @param none
   * @return none
   */
  abstract public function enableTestMode();

  /**
   * Validate the notification
   *
   * @param none
   * @return boolean
   */
  abstract protected function validateNotification();

  /**
   * Set product (use this for single item)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  abstract function setProduct($reference, $name, $amount, $quantity = 1);

  /**
   * Add product (use this for multiple items)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  abstract function addProduct($reference, $name, $amount, $quantity = 1);

  /**
   * Get Item (use this for single item)
   *
   * @param none
   * @return sfPaymentItemInterface
   */
  abstract function getItem();

  /**
   * Get Item(s) Cart
   *
   * @param none
   * @return sfPaymentCartInterface
   */
  abstract function getCart();

  /**
   * Return value of field
   *
   * @param string
   * @return mixed
   */
  abstract function getField($name);

  /**
   * Set value for field $name
   *
   * @param string
   * @param mixed
   * @return none
   */
  abstract function setField($name, $value);

}

?>