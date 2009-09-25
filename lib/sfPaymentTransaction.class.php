<?php

/**
 * sfPaymentTransaction
 *
 * This class is used by the developer implementing the online payment solution. It is a service
 * container for the sfPaymentGateway object, $gateway and the sfPaymentStorage $storage.
 *
 * @package     sfPaymentTransaction
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

class sfPaymentTransaction {

  const TRANSACTION_COMPLETED = 'Completed';
  const TRANSACTION_PENDING = 'Pending';
  const TRANSACTION_FAILED = 'Failed';
  const TRANSACTION_INVALID = 'Invalid';
  const HTTPERROR = 'HttpError';
  const UNKNOWERROR = 'UnknowError';

  const PAYPAL = 'PayPal';

  const DOCTRINE_STORAGE = 'Doctrine';

  /**
   * Initialize the plugin by configuration file
   *
   * @param none
   * @return none
   */
  public static function init() {
    $cart_class = sfConfig::get('app_sf_payment_plugin_cartClass');
    if($cart_class) {
      $class = new ReflectionClass($cart_class);
      if (!$class->implementsInterface(sfPaymentCartInterface))
        throw new sfException($class->getName().' must implement sfPaymentCartInterface');

      sfPaymentTransaction::$CART_CLASS = $cart_class;
    }

    $item_class = sfConfig::get('app_sf_payment_plugin_itemClass');
    if($item_class) {
      $class = new ReflectionClass($item_class);
      if (!$class->implementsInterface(sfPaymentItemInterface))
        throw new sfException($class->getName().' must implement sfPaymentItemInterface');

      sfPaymentTransaction::$ITEM_CLASS = $item_class;
    }
  }

  /**
   * Get cartClass set on configuration file
   *
   * @param none
   * @return string
   */
  private static function getCartClass() {
    if(!sfPaymentTransaction::$CART_CLASS)
      throw new sfException('Class for cart not found. Did you declare it in configuration?');

    return sfPaymentTransaction::$CART_CLASS;
  }

  /**
   * Get itemClass set on configuration file
   *
   * @param none
   * @return string
   */
  private static function getItemClass() {
    if(!sfPaymentTransaction::$ITEM_CLASS)
      throw new sfException('Class for item not found. Did you declare it in configuration?');

    return sfPaymentTransaction::$ITEM_CLASS;
  }

  /**
   * PaymentGateway
   *
   * @var sfPaymentAbstractGateway
   */
  private $gateway;

  /**
   * PaymentStorage
   *
   * @var sfPaymentStorageInterface
   */
  private $storage;

  function __construct($gateway_name = NULL, $storage_name = NULL){
    if($gateway_name) $this->setPaymentGateway($gateway_name);
    if($storage_name) $this->setPaymentStorage($storage_name);
  }

  /**
   * Set gateway class by name
   *
   * @param string
   * @return none
   */
  public function setPaymentGateway($gateway_name) {
    switch($gateway_name) {
      case self::PAYPAL:
        $gateway_class = 'sfPaymentPayPalGateway';
        break;
      default:
        throw new Exception('Unknow gateway');
    }
    $this->gateway = new $gateway_class();
  }

  /**
   * Set storage class by name
   *
   * @param string
   * @return none
   */
  public function setPaymentStorage($storage_name) {
    switch($storage_name) {
      case self::DOCTRINE_STORAGE:
        $storage_class = 'sfPaymentDoctrineStorageTable';
      default:
        throw new Exception('Unknow storage');
    }

    $this->storage = new $storage_class();
  }

  /**
   * Return gateway's instance
   *
   * @param none
   * @return sfPaymentAbstractGateway
   */
  public function getGateway() {
    return $this->gateway;
  }

  /**
   * Set gateway's instance
   *
   * @param sfPaymentAbstractGateway
   * @return none
   */
  public function setGateway(sfPaymentAbstractGateway $gateway) {
    $this->gateway = $gateway;
  }

  /**
   * Set storage's instance
   *
   * @param sfPaymentStorageInterface
   * @return none
   */
  public function setStorage(sfPaymentStorageInterface $storage) {
    $this->storage = $storage;
  }

  /**
   * Return gateway's url
   *
   * @param none
   * @return url
   */
  public function getGatewayUrl() {
    return $this->gateway->getUrl();
  }

  /**
   * Return fields array
   *
   * @return array
   */
  public function getFields() {
    return $this->gateway->getFields();
  }

  /**
   * Get transaction's currency (ex. USD)
   *
   * @param none
   * @return string
   */
  public function getCurrency() {
    return $this->gateway->getCurrency();
  }

  /**
   * Set the value of currency.
   *
   * @param      string
   * @return     none
   */
  public function setCurrency($currency) {
    $this->gateway->setCurrency($currency);
  }

  /**
   * Set amount for shipping
   *
   * @param float
   * @return none
   */
  public function setShipping($amount) {
    $this->gateway->setShipping($amount);
  }

  /**
   * Get transaction's reference
   *
   * @param none
   * @return string
   */
  public function getReference() {
    return $this->gateway->getReference();
  }

  public function getType() {
    $this->gateway->getType();
  }

  /**
   * Get transaction's date
   *
   * @param none
   * @return timestamp
   */
  public function getDate() {
    return $this->gateway->getDate();
  }

  /**
   * Get transaction's status
   *
   * @param none
   * @return string
   */
  public function getStatus() {
    return $this->gateway->getStatus();
  }

  /**
   * Get buyer's account that made the transfer
   *
   * @param none
   * @return string
   */
  public function getBuyer() {
    return $this->gateway->getBuyer();
  }

  /**
   * Get seller's account where the money is transferred
   *
   * @param none
   * @return string
   */
  public function getVendor() {
    return $this->gateway->getVendor();
  }

  /**
   * Get total amount of product(s) (ex. '100 USD')
   *
   * @param none
   * @return string
   */
  public function getTotalAmount(){
    return $this->gateway->getTotalAmount();
  }

  /**
   * Set product (use this for single item)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  public function setProduct($reference, $name, $amount, $quantity = 1) {
    $this->gateway->setProduct($reference, $name, $amount, $quantity);
  }

  /**
   * Add product (use this for multiple items)
   *
   * @param reference
   * @param name
   * @param amount
   * @param quantity
   */
  public function addProduct($reference, $name, $amount, $quantity = 1) {
    $this->gateway->addProduct($reference, $name, $amount, $quantity);
  }

  /**
   * Set product by item to fields (for single item)
   *
   * @param item (sfPaymentItemInterface)
   */
  public function setProductByItem(sfPaymentItemInterface $item) {
    $this->setProduct(
      $item.getReference(),
      $item.getName(),
      $item.getPrice(),
      $item.getQuantity
    );
  }

  /**
   * Add product by item to fields (for multiple items)
   *
   * @param item (sfPaymentItemInterface)
   */
  public function addProductByItem(sfPaymentItemInterface $item) {
    $this->addProduct(
      $item.getReference(),
      $item.getName(),
      $item.getPrice(),
      $item.getQuantity
    );
  }

  /**
   * Get Item (use this for single item)
   *
   * @param none
   * @return sfPaymentItemInterface
   */
  public function getItem() {
    return $this->gateway->getItem();
  }

  /**
   * Get Item(s) Cart
   *
   * @param none
   * @return sfPaymentCartInterface
   */
  public function getCart() {
    return $this->gateway->getCart();
  }

  /**
   * Add products contents in cart to fields (for multiple items)
   *
   * @param cart
   */
  public function setCart(sfPaymentCartInterface $cart) {
    foreach($cart->getItems as $item) {
      $this->addProductByItem($item);
    }
  }

  /**
   * Set validation fields received from PayPal
   *
   * @param array
   * @return none
   */
  public function setValidationFields($fields) {
    $this->gateway->setValidationFields();
  }

  /**
   * Validate the notification
   *
   * @param none
   * @return boolean
   */
  public function validateNotification() {
    return $this->gateway->validateNotification();
  }

  /**
   * Save the transaction using the injected storage
   *
   * @param none
   * @return boolean
   */
  public function save() {
    if(!$this->storage)
      throw new Exception("Storage not declared");

    return $storage->saveTransaction($this);
  }

}
sfPaymentTransaction::init();
?>