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

class sfPaymentTransaction
{

  const TRANSACTION_COMPLETED = 'Completed';
  const TRANSACTION_PENDING = 'Pending';
  const TRANSACTION_FAILED = 'Failed';
  const TRANSACTION_INVALID = 'Invalid';
  const HTTPERROR = 'HttpError';
  const UNKNOWERROR = 'UnknowError';

  const PAYPAL = 'PayPal';

  const DOCTRINE_STORAGE = 'Doctrine';

  /**
   * Get cartClass set on configuration file
   *
   * @param none
   * @return string
   */
  public static function getCartClass()
  {
    $cart_class = sfConfig::get('app_sf_payment_plugin_cart_class');
    if($cart_class)
    {
      $class = new ReflectionClass($cart_class);
      if (!$class->implementsInterface('sfPaymentCartInterface'))
        throw new sfException($class->getName().' must implement sfPaymentCartInterface');
    }
    else
    {
      throw new sfException('Class for cart not found. Did you declare it in configuration?');
    }

    return $cart_class;
  }

  /**
   * Get itemClass set on configuration file
   *
   * @param none
   * @return string
   */
  public static function getItemClass()
  {
    $item_class = sfConfig::get('app_sf_payment_plugin_item_class');
    if($item_class)
    {
      $class = new ReflectionClass($item_class);
      if (!$class->implementsInterface('sfPaymentItemInterface'))
        throw new sfException($class->getName().' must implement sfPaymentItemInterface');
    }
    else
    {
      throw new sfException('Class for item not found. Did you declare it in configuration?');
    }

    return $item_class;
  }

  /**
   * sfPaymentGateway
   *
   * @var sfPaymentGateway
   */
  private $gateway;

  /**
   * sfPaymentStorageInterface
   *
   * @var string
   */
  private $storage_name;

  /**
   * is validated
   *
   * @var boolean
   */
  private $is_validated;

  function __construct($gateway_name = null, $storage_name = null)
  {
    if($gateway_name) $this->setPaymentGateway($gateway_name);
    if($storage_name) $this->setPaymentStorage($storage_name);
  }

  /**
   * Set gateway class by name
   *
   * @param string
   * @return none
   */
  public function setPaymentGateway($gateway_name)
  {
    switch($gateway_name)
    {
      case self::PAYPAL:
        $gateway_class = 'sfPaymentGatewayPayPal';
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
  public function setPaymentStorage($storage_name)
  {
    switch($storage_name)
    {
      case self::DOCTRINE_STORAGE:
        break;
      default:
        throw new Exception('Unknow storage');
    }
    $this->storage_name = $storage_name;
  }

  /**
   * Return gateway's instance
   *
   * @param none
   * @return sfPaymentAbstractGateway
   */
  public function getGateway()
  {
    return $this->gateway;
  }

  /**
   * Set gateway's instance
   *
   * @param sfPaymentAbstractGateway
   * @return none
   */
  public function setGateway(sfPaymentGateway $gateway)
  {
    $this->gateway = $gateway;
  }

  /**
   * Set storage's instance
   *
   * @param sfPaymentStorageInterface
   * @return none
   */
  public function setStorage(sfPaymentStorageInterface $storage)
  {
    $this->storage = $storage;
  }

  /**
   * Return gateway's url
   *
   * @param none
   * @return url
   */
  public function getGatewayUrl()
  {
    return $this->gateway->getUrl();
  }

  /**
   * Return fields array
   *
   * @return array
   */
  public function getFields()
  {
    return $this->gateway->getFields();
  }

  /**
   * Get transaction's currency (ex. USD)
   *
   * @param none
   * @return string
   */
  public function getCurrency()
  {
    return $this->gateway->getCurrency();
  }

  /**
   * Set the value of currency.
   *
   * @param      string
   * @return     none
   */
  public function setCurrency($currency)
  {
    $this->gateway->setCurrency($currency);
  }

  /**
   * Set amount for shipping
   *
   * @param float
   * @return none
   */
  public function setShipping($amount)
  {
    $this->gateway->setShipping($amount);
  }

  /**
   * Get transaction's reference
   *
   * @param none
   * @return string
   */
  public function getReference()
  {
    return $this->gateway->getReference();
  }

  public function getType()
  {
    return $this->gateway->getType();
  }

  /**
   * Get transaction's date
   *
   * @param none
   * @return timestamp
   */
  public function getDate()
  {
    return $this->gateway->getDate();
  }

  /**
   * Get transaction's status
   *
   * @param none
   * @return string
   */
  public function getStatus()
  {
    return $this->gateway->getStatus();
  }

  /**
   * Get buyer's account that made the transfer
   *
   * @param none
   * @return string
   */
  public function getBuyer()
  {
    return $this->gateway->getBuyer();
  }

  /**
   * Get seller's account where the money is transferred
   *
   * @param none
   * @return string
   */
  public function getVendor()
  {
    return $this->gateway->getVendor();
  }

  /**
   * Get total amount of product(s) (ex. '100 USD')
   *
   * @param none
   * @return string
   */
  public function getTotalAmount()
  {
    return $this->gateway->getTotalAmount().' '.$this->gateway->getCurrency();
  }

  /**
   * Get Item (use this for single item)
   *
   * @param none
   * @return sfPaymentItemInterface
   */
  public function getItem()
  {
    return $this->gateway->getItem();
  }

  /**
   * Get Item(s) Cart
   *
   * @param none
   * @return sfPaymentCartInterface
   */
  public function getCart()
  {
    return $this->gateway->getCart();
  }

  /**
   * Set product (use this for single item)
   *
   * @param mixed
   * @param string
   * @param float
   * @param int
   * @return none
   */
  public function setProduct($reference, $name, $amount, $quantity = 1)
  {
    $this->gateway->setProduct($reference, $name, $amount, $quantity);
  }

  /**
   * Set product by item (use this for single item)
   *
   * @param sfPaymentItemInterface
   * @return none
   */
  public function setItem(sfPaymentItemInterface $item)
  {
    $this->setProduct(
      $item->getReference(),
      $item->getName(),
      $item->getPrice(),
      $item->getQuantity()
    );
  }

  /**
   * Add product (use this for multiple items)
   *
   * @param mixed
   * @param string
   * @param float
   * @param int
   * @return none
   */
  public function addProduct($reference, $name, $amount, $quantity = 1)
  {
    $this->gateway->addProduct($reference, $name, $amount, $quantity);
  }

  /**
   * Add product by item (use this for multiple items)
   *
   * @param sfPaymentItemInterface
   * @return none
   */
  public function addItem(sfPaymentItemInterface $item)
  {
    $this->addProduct(
      $item->getReference(),
      $item->getName(),
      $item->getPrice(),
      $item->getQuantity()
    );
  }

  /**
   * Add products contents in cart (for multiple items)
   *
   * @param sfPaymentCartInterface
   * @return none
   */
  public function setCart(sfPaymentCartInterface $cart)
  {
    foreach($cart->getItems() as $item)
    {
      $this->addItem($item);
    }
  }

  /**
   * Set validation fields received from PayPal
   *
   * @param array
   * @return none
   */
  public function setValidationFields($fields)
  {
    $this->gateway->setValidationFields($fields);
  }

  /**
   * Validate the notification
   *
   * @param none
   * @return boolean
   */
  public function validateNotification()
  {
    $this->is_validated = true;

    return $this->gateway->validateNotification();
  }

  /**
   * Save the transaction using the injected storage
   *
   * @param none
   * @return boolean
   */
  public function save()
  {
    if(!$this->is_validated)
    {
      throw new Exception("Can't save unvalidated transaction");
    }
    if(!$this->storage_name)
    {
      throw new Exception("Storage not declared");
    }

    switch($this->storage_name)
    {
      case self::DOCTRINE_STORAGE:
        return Doctrine::getTable('sfPaymentDoctrineStorage')->saveTransaction($this);
    }
  }

}
?>