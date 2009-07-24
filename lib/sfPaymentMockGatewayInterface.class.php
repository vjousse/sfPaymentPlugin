<?php
/**
 * sfPaymentMockGatewayInterface
 *
 * This class is a mock of the gateway interface. Used mainly for tests. 
 *
 * @package     sfPaymentGatewayInterface
 * @category    Library
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

require_once sfConfig::get('sf_plugins_dir').'/sfPaymentPlugin/lib/sfPaymentGatewayInterface.php';

class sfPaymentMockGatewayInterface extends sfPaymentGatewayInterface {

	/**
	 * Construct a mock object with standard values
	 * 
	 * @return null
	 */
  public function __construct() {
    parent::__construct();
    
    // translation table
    $this->addFieldTranslation('Vendor',          'Vendor');
    $this->addFieldTranslation('Currency',        'Currency');
    $this->addFieldTranslation('Amount',          'Amount');
    $this->addFieldTranslation('ProductName',     'ProductName');
    $this->addFieldTranslation('ProductPrice',    'ProductPrice');
    $this->addFieldTranslation('ProductQuantity', 'ProductQuantity');

    // default values of the class
    $this->gatewayUrl = 'http://localhost/mock';
  }
	
  /**
   * Set test mode
   * 
   * @return null
   */
  protected function enableTestMode()
  {
    $this->testMode = true;
  }
  
  /**
   * Validate IPN
   * 
   * @return null
   */
  protected function validateIpn()
  {
    
  }
}