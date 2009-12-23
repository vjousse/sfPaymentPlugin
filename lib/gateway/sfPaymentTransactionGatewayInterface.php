<?php

  /**
   * Interface for gateway implementations.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfPaymentTransactionGatewayInterface
  {

    /**
     * Check if the gateway accepts a specific currency.
     *
     * @return  boolean
     */
    function canAcceptCurrency ($arg_currency);

    /**
     * Prepare the transaction.
     *
     * @return  void
     */
    function prepare ();

    /**
     * Request a transaction from the provider.
     *
     * @param   sfTransactionInterface  $arg_transaction  The transaction object.
     *
     * @return  void
     */
    function request (sfPaymentTransactionInterface $arg_transaction);

    /**
     * Process the transaction.
     *
     * @param   sfTransactionInterface  $arg_transaction  The transaction object.
     *
     * @return  void
     */
    function process ();

    /**
     * Check if the gateway is enabled.
     *
     * @return  boolean
     */
    function isEnabled ();

  }