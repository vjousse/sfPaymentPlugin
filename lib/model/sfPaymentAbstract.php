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
   * Return value of field
   *
   * @param string
   * @return mixed
   */
  abstract function getField($name);

  abstract function setField($name, $value);

  /**
   * Get transaction's date
   *
   * @param none
   * @return timestamp (format???)
   */
  abstract function getDate();

  /**
   * Get transaction's status
   *
   * @param none
   * @return string
   */
  abstract function getStatus();

  /**
   * Get transaction's currency (ex. USD)
   *
   * @param none
   * @return string
   */
  public function getCurrency() {
    return $this->getField('currency');
  }

  /**
   * Set the value of currency.
   *
   * @param      string
   * @return     none
   */
  public function setCurrency($currency) {
    $this->setField('currency', $currency);
  }

  /**
   * Set amount for shipping
   *
   * @param float
   * @return none
   */
  public function setShipping($amount) {
    $this->setField('shipping', $amount);
  }

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
   * Get Cart (use this for multiple items)
   *
   * @param none
   * @return sfPaymentCartInterface
   */
  abstract function getCart();

}

?>