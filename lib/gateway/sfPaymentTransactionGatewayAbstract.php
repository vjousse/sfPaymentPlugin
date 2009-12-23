<?php

  /**
   * sfPaymentTransactionGatewayAbstract
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  abstract class sfPaymentTransactionGatewayAbstract implements sfPaymentTransactionGatewayInterface
  {

    /**
     * @var array
     */
    protected $_acceptedCurrencies;

    /**
     * @var boolean
     */
    protected $_enabled;

    /**
     * Check if the gateway accepts a specific currency.
     *
     * @param   string  $arg_currency The currency to check.
     *
     * @return  boolean
     */
    public function canAcceptCurrency ($arg_currency)
    {
      return in_array($arg_currency, $this->_acceptedCurrencies);
    }

    /**
     * Check if the gateway is enabled.
     *
     * @return  boolean
     */
    public function isEnabled ()
    {
      return $this->_enabled;
    }

    /**
     * Generate a string representation for the gateway object.
     *
     * @return  String
     */
    public function __toString ()
    {
      return get_class($this);
    }

  }