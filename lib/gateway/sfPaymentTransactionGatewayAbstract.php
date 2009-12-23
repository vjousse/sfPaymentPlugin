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
     * @var sfWebBrowserInterface
     */
    private $_browser;

    /**
     * @var array
     */
    protected $_acceptedCurrencies;

    /**
     * @var boolean
     */
    protected $_enabled;

    /**
     * Adapter construction.
     *
     * @param   sfWebBrowserInterface $arg_browser  The browser implementation.
     * @param   array                 $arg_options  The options.
     *
     * @return  void
     *
     * Options:
     *   partner_id - The required mollie partner id.
     *   test_modus - The value indicating whether or not to use the testmodus.
     */
    public function __construct (sfWebBrowserInterface $arg_browser, array $arg_options)
    {
      $this->_browser = $arg_browser;
      $this->_enabled = FALSE;

      if (isset($arg_options['enabled']))
      {
        if ( ! is_bool($arg_options['enabled']))
        {
          throw new InvalidArgumentException(sprintf('The "%s" class expects a boolean value for the "enabled" configuration value.'));
        }

        $this->_enabled = $arg_options['enabled'];
      }
    }

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