<?php

/**
 * sfPaymentGatewayInterface
 *
 * This abstract class provides the default variables and methods used in 
 * gateway support classes.
 *
 * @package     sfPaymentGatewayInterface
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

// url helper needed for the address translations using symfony routes
require_once sfConfig::get('sf_symfony_lib_dir')."/helper/UrlHelper.php";

abstract class sfPaymentGatewayInterface
{
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
   * Returns the fields
   * 
   * @return unknown_type
   */
  public function getFields()
  {
  	return $this->fields;
  }
  
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
}