<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginsfPaymentTransaction extends BasesfPaymentTransaction
{
  const TRANSACTION_COMPLETED = 'Completed';
  const TRANSACTION_PENDING = 'Pending';
  const TRANSACTION_FAILED = 'Failed';
  const TRANSACTION_INVALID = 'Invalid';
  const HTTPERROR = 'HttpError';
  const UNKNOWERROR = 'UnknowError';

  const PAYPAL = 'PayPal';

  /**
   * Initialize the plugin by configuration file
   *
   * @param none
   * @return none
   */
  public function setUp() {
    parent::setUp();

    $cart_class = sfConfig::get('app_sf_payment_plugin_cart_class');
    if($cart_class) {
      $class = new ReflectionClass($cart_class);
      if (!$class->implementsInterface(sfPaymentCartInterface))
        throw new sfException($class->getName().' must implement sfPaymentCartInterface');

      sfPaymentTransaction::$CART_CLASS = $cart_class;
    }

    $item_class = sfConfig::get('app_sf_payment_plugin_item_class');
    if($item_class) {
      $class = new ReflectionClass($item_class);
      if (!$class->implementsInterface(sfPaymentItemInterface))
        throw new sfException($class->getName().' must implement sfPaymentItemInterface');

      sfPaymentTransaction::$ITEM_CLASS = $item_class;
    }
  }

  public static function getPaymentClass($obj) {
    if(!($obj instanceof sfPaymentTransaction)) return NULL;
    switch($obj->_get('type')) {
      case self::PAYPAL:
        return 'sfPaymentPayPal';
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
   * Payment gateway URL
   *
   * @var string
   */
  private $gatewayUrl;

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
   * Returns fields
   *
   * @return array
   */
  public function getFields()
  {
    return $this->fields;
  }

  protected function setFields($fields) {
    foreach($fields as $key => $value) {
      $this->fields[$key] = $value;
    }
  }

  /**
   * Get transaction's reference
   *
   * @param none
   * @return string
   */
  public function getReference() {
    if(!$this->_get('reference'))
      return $this->_get('type').$this->getField('reference');

    return $this->_get('reference');
  }

  /**
   * Get buyer's account that made the transfer
   *
   * @param none
   * @return string
   */
  public function getBuyer() {
    if(!$this->_get('buyer')) return $this->getField('buyer');

    return $this->_get('buyer');
  }

  /**
   * Get seller's account where the money is transferred
   *
   * @param none
   * @return string
   */
  public function getVendor() {
    if(!$this->_get('vendor')) return $this->getField('vendor');

    return $this->_get('vendor');
  }

  /**
   * Get total amount of product(s) (ex. '100 USD')
   *
   * @param none
   * @return string
   */
  public function getTotalAmount(){
    if(!$this->_get('total_amount'))
      return $this->getField('total_amount').$this->getField('currency');

    return $this->_get('total_amount');
  }

  public function getParams() {
    return serialize($this->getFields());
  }

  public function setParams($params) {
    $this->setFields(unserialize($params));
  }

  public function getGatewayUrl() {
    return $this->gatewayUrl;
  }

  protected function setGatewayUrl($url) {
    $this->gatewayUrl = $url;
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
   * Add products contents in cart to fields (for multiple items)
   *
   * @param cart
   */
  public function setCart(sfPaymentCartInterface $cart) {
    foreach($cart->getItems as $item) {
      $this->addProductByItem($item);
    }
  }

}

?>