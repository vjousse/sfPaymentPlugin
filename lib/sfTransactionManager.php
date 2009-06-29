<?php

  /**
   * sfTransactionManager.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   *
   * Eventually this class should just be removed and its methods should be tied
   * into the event system from within the plugin config class. For now I've just
   * used this as a manager.
   */
  class sfTransactionManager
  {

    /**
     * @var sfTransactionAdapterInterface
     */
    private $_adapter;

    /**
     * @var sfTransactionInterface
     */
    private $_transaction;

    /**
     * Manger construction.
     *
     * @param   $arg_adapter  sfTransactionAdapterInterface The transaction adapter.
     *
     * @return                void
     */
    public function __construct (sfTransactionAdapterInterface $arg_adapter)
    {
      $this->_adapter     = $arg_adapter;
      $this->_transaction = array();
    }

    /**
     * Prepare the transaction.
     */
    public function prepare ()
    {
      return $this->_adapter->prepare();
    }

    /**
     * Request the transaction.
     *
     * @param   $arg_transaction  sfTransactionInterface
     */
    public function request (sfTransactionInterface $arg_transaction)
    {
      return $this->_adapter->request($arg_transaction);
    }

    /**
     * Process the transaction.
     */
    public function process ()
    {
      return $this->_adapter->prepare();
    }
  }