<?php
/**
 * sfPaymentTransaction
 *
 * This class is used by the developer implementing the online payment solution. It is a service
 * container for the sfPaymentGatewayInterface object, $gateway. 
 *
 * @package     sfPaymentGatewayInterface
 * @category    Library
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

class sfPaymentTransaction {
	
	/**
	 * Gateway: instance of sfPayment(sfPaymentGatewayInterface)
	 *
	 * @var gateway
	 */
	public $gateway;
	
	/**
	 * Constructor
	 *
	 * @param Gateway $gateway
	 */
	public function __construct(sfPaymentGatewayInterface $gateway)
	{
		$this->gateway = $gateway;
	}
	
	/**
	 * Get the gateway object
	 *
	 * @return gateway object
	 */
	public function getGateway()
	{
		return $this->gateway;
	}
	
	/**
	 * Set the gateway object
	 *
	 * @param Gateway $gateway
	 */
	public function setGateway($gateway)
	{
		$this->gateway = $gateway;
	}
	
	/**
	 * Prepare proxy methods : methods return to gateway object
	 * $transaction->setCurrency() translates to $transaction->getGateway()->setCurrency() 
	 *
   * @param $name
   * @param $arguments
   * @return mixed
	 */
	public function __call($name, $arguments)
	{
		if(isset($this->gateway))
		  return call_user_func_array(array($this->gateway, $name),$arguments);
		else
		  throw new sfException('Gateway does not exist.');
	}
}