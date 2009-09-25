<?php

/**
 * sfPaymentAbstractGateway
 *
 * This abstract class provides the default variables and methods used in
 * gateway support classes.
 *
 * @package     sfPaymentAbstractGateway
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

abstract class sfPaymentAbstractGateway {

  /**
   * Payment gateway URL
   *
   * @var string
   */
  private $url;

  /**
   * Are we in test mode ?
   *
   * @var boolean
   */
  protected $testMode = false;

  /**
   * Field content to submit to gateway
   *
   * @var array
   */
  protected $fields = array();

  /**
   * Get gateway's url
   *
   * @param none
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Set gateway's url
   *
   * @param string
   * @return none
   */
  protected function setUrl($url) {
    $this->url = $url;
  }

  /**
   * Return fields array
   *
   * @return array
   */
  public function getFields() {
    return $this->fields;
  }

  /**
   * Add fields array
   *
   * @param array
   * @return none
   */
  protected function setFields($fields) {
    foreach($fields as $key => $value) {
      $this->fields[$key] = $value;
    }
  }

  /**
   * Set validation fields received from PayPal
   *
   * @param array
   * @return none
   */
  abstract function setValidationFields($fields);

  /**
   * Validate the notification
   *
   * @param none
   * @return boolean
   */
  abstract public function validateNotification();

  abstract function getType();

  /**
   * Get transaction's date
   *
   * @param none
   * @return timestamp
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
   * Set product (use this for single item)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  abstract function setProduct($reference, $name, $amount, $quantity);

  /**
   * Add product (use this for multiple items)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  abstract function addProduct($reference, $name, $amount, $quantity);

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
   * Translate field's name
   *
   * @param string
   * @return string
   */
  abstract function translateField($name);

  /**
   * Return value of field
   *
   * @param string
   * @return mixed
   */
  public function getField($name) {
    $fieldName = $this->transaleField($name);
    if($fieldName) return $this->fields[$fieldName];

    throw new sfException('No field\'s translaction for  '.$name.'.');
  }

  /**
   * Set value for field $name
   *
   * @param string
   * @param mixed
   * @return none
   */
  public function setField($name, $value) {
    $fieldName = $this->translateField($name);
    if($fieldName) return $this->fields[$fieldName] = $value;

    throw new sfException('No field\'s translaction for  '.$name.'.');
  }

  /**
   * Proxy methods:
   * Methods called by sfPaymentTransaction to its gateway object
   * $this->getVendor() returns $this->getField('vendor')
   *
   * @param $name
   * @param $arguments
   */
  public function __call($name, array $arguments) {
    // parse name
    if(preg_match('/(get|set)([a-zA-Z]+)/', $name, $matches))
    {
      // set or get ?
      switch($matches[1])
      {
        case 'get':
          return $this->getField($matches[2]);
        case 'set':
          return $this->setField($matches[2], $arguments[0]);
      }
    }
    throw new sfException('Method '.$name.' doesn\'t exist.');
  }

}

?>