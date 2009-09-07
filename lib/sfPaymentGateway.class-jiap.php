<?php

/**
 * sfPaymentGatewayInterface
 *
 * This abstract class provides the default variables and methods used in
 * gateway support classes.
 *
 * @package     sfPaymentGateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

// url helper needed for the address translations using symfony routes
require_once sfConfig::get('sf_symfony_lib_dir')."/helper/UrlHelper.php";

abstract class sfPaymentGateway
{
  private static $logger;
  protected static $name;

  /**
   * Holds the last error encountered
   *
   * @var string
   */
  public $lastError = '';

  /**
   * Payment gateway IPN response
   *
   * @var string
   */
  public $ipnResponse;

  /**
   * Are we in test mode ?
   *
   * @var boolean
   */
  public $testMode;

  /**
   * Field content to submit to gateway
   *
   * @var array
   */
  public $fields = array();

  /**
   * Array holding the fields translations
   *
   * @var array
   */
  public $field_translation = array();

  /**
   * IPN post values as array
   *
   * @var array
   */
  public $ipnData = array();

  /**
   * Payment gateway URL
   *
   * @var string
   */
  public $gatewayUrl;

  /**
   * Initialization constructor
   *
   * @param none
   * @return void
   */
  public function __construct()
  {
    // some default values of the class
    $this->lastError = '';
    $this->logIpn = TRUE;
    $this->ipnResponse = '';
    $this->testMode = FALSE;

    // translation table
    $this->field_translation = array();
  }

  /**
   * Adds a key=>value pair to the fields array
   *
   * @param string key of field
   * @param string value of field
   * @return
   */
  public function addFieldTranslation($field, $value)
  {
    $this->field_translation[$field] = $value;
  }

  /**
   * Submit payment request
   *
   * Generates a form with hidden elements from the fields array
   * and submits it to the payment gateway URL. The user is presented
   * a redirecting message along with a button to click.
   *
   * @param none
   * @return void
   */
  public function submitPayment()
  {
    $this->prepareSubmit();
  }

  /**
   * Perform any pre-posting actions
   *
   * @param none
   * @return none
   */
  protected function prepareSubmit()
  {
  }

  /**
   * Enables the test mode
   *
   * @param none
   * @return none
   */
  abstract protected function enableTestMode();

  /**
   * Validate the IPN notification
   *
   * @param none
   * @return boolean
   */
  abstract protected function validateIpn();

  /**
   * Set parameters to fields
   *
   * @param params
   * @return none
   */
  protected function setParameters($params) {
    foreach(array_keys($params) as $key) {
      $this->fields[$key] = $params[$key];
    }
  }

  /**
   * Returns the fields
   *
   * @return unknown_type
   */
  public function getFields()
  {
    return $this->fields;
  }

  /**
   * Add field (name, value) to fields
   *
   * @param name
   * @param value
   * @return none
   */
  protected function addField($name, $value) {
    $this->fields[$name] = $value;
  }

  /**
   * Set product to fields (for single item)
   *
   * @param name
   * @param amount
   * @param quantity
   * @param cod
   */
  abstract function setProduct($name, $amount, $quantity = 1, $cod = 0);

  /**
   * Add product to fields (for multiple items)
   *
   * @param name
   * @param amount
   * @param quantity
   * @param cod
   */
  abstract function addProduct($name, $amount, $quantity = 1, $cod = 0);

  /**
   * Logs the IPN results
   *
   * @param boolean IPN result
   * @return void
   */
  public function logResults($success)
  {
    if (!$this->logIpn) return;

    // return if no instance is available
    if (!sfContext::hasInstance()) return;

    sfContext::getInstance()->getLogger()->log('Validating Payment');

    sfContext::getInstance()->getLogger()->log(($success) ? "Success!" : 'Failure: ' . $this->lastError);

    // log the POST variables
    sfContext::getInstance()->getLogger()->log("IPN POST Vars from gateway:");
    foreach ($this->ipnData as $key=>$value)
    {
      sfContext::getInstance()->getLogger()->log($key.' = '.$value);
    }

    // log the response from the paypal server
    sfContext::getInstance()->getLogger()->log("IPN Response from gateway Server: " . $this->ipnResponse);
  }

  /**
   * Proxy methods:
   * Methods to sfPaymentTransaction called to its gateway object
   * $this->getVendor() returns $this->getField('business')
   *
   * @param $name
   * @param $arguments
   */
  public function __call($name, array $arguments) {
    // analyze name
    if(preg_match('/(get|set)([a-zA-Z]+)/', $name, $matches))
    {
      // check if translation exists
      if(isset($this->field_translation[$matches[2]]))
      {
        // set or get ?
        switch($matches[1])
        {
          case 'get':
            return $this->fields[$this->field_translation[$matches[2]]];
            break;
          case 'set':
            return $this->fields[$this->field_translation[$matches[2]]] = $arguments[0];
            break;
        }
      }
      else
      {
        throw new sfException('Method '.$name.' doesn\'t exist. ($field_translation[\''.$matches[2].'\'] not found)');
      }
    }
    throw new sfException('Method '.$name.' doesn\'t exist.');
  }

  /* STATIC FUNCTION */

  protected static function enableLog($logFile) {
    // initialize the logger and set the file we want to log to
    self::$logger = new sfFileLogger(new sfEventDispatcher(), array('file' => sfConfig::get('sf_root_dir').'/log/'.$logFile));
  }

  public static function getLogger() {
    return self::$logger;
  }

  public static function log($message) {
    if (!self::$logger) return;

    self::$logger->log('{'.self::$name.'} '.$message);
  }

  /*
  public static function logNotification($data) {
    $logger = self::getLogger();
    if (!$logger) return;

    $logger->log(self::getName().': Validating Payment');
    $logger->log(($data->success) ? '\tSuccess!' : '\tFailure: '.$data->error);
    $logger->log('\n');

    // log the POST variables
    $logger->log(self::getName().': POST Variables');
    foreach ($data as $key=>$value)
    {
      $logger->log('\t'.$key.' = '.$value);
    }
    $logger->log('\n');

    // log the response from the paypal server
    $logger->log(self::getName().': Response from server ['.$data->response.']');
  }
  */
}