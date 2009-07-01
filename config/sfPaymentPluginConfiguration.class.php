<?php

  /**
   * sfPaymentPlugin configuration.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentPluginConfiguration extends sfPluginConfiguration
  {

    /**
     * @var sfTransactionAdapterInterface
     */
    private $_transactionAdapter;

    /**
     * Plugin initialization.
     *
     * @return  void
     */
    public function initialize ()
    {
      $this->dispatcher->connect('transaction.prepare', array($this, 'onTransactionPrepare'));
      $this->dispatcher->connect('transaction.request', array($this, 'onTransactionRequest'));
      $this->dispatcher->connect('transaction.process', array($this, 'onTransactionProcess'));
    }

    /**
     * Get the transaction adapter.
     *
     * @return  sfTransactionAdapterInterface A transaction adapter implementation.
     */
    public function getTransactionAdapter ()
    {
      if (NULL === $this->_transactionAdapter)
      {
        $transactionAdapterClass   = sfConfig::get('app_transaction_adapter_class', 'sfTransactionAdapterMock');
        $browserClass              = sfConfig::get('app_transaction_browser_class', 'sfWebBrowserMock');
        $this->_transactionAdapter = new $transactionAdapterClass(new $browserClass());
      }

      return $this->_transactionAdapter;
    }

    /**
     * Event listener for transaction.prepare event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionPrepare (sfEvent $arg_event)
    {
      $arg_event->setReturnValue($this->getTransactionAdapter()->prepare());
    }

    /**
     * Event listener for transaction.request event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionRequest (sfEvent $arg_event)
    {
      $this->_assertTransactionEvent($arg_event);

      $arg_event->setReturnValue($this->getTransactionAdapter()->request($arg_event['transaction']));
    }

    /**
     * Event listener for transaction.process event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionProcess (sfEvent $arg_event)
    {
      $this->_assertTransactionEvent($arg_event);

      $arg_event->setReturnValue($this->getTransactionAdapter()->process($arg_event['transaction']));
    }

    /**
     * Event listener for transaction.process event.
     *
     * @param   sfEvent                 $arg_event  Event object.
     *
     * @throws  sfTransactionException              In case no transaction implementation was passed along with the event.
     *
     * return   void
     */
    private function _assertTransactionEvent (sfEvent $arg_event)
    {
      if ( ! isset($arg_event['transaction']) || $arg_event['transaction'] instanceof rsfTransactionInterface)
      {
        throw new sfTransactionException('You should pass a rsfTransactionInterface implementation along with the event.');
      }
    }

  }