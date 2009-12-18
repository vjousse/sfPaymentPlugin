<?php

  /**
   * Interface for gateway implementations.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfTransactionGatewayInterface
  {

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
    function request (sfTransactionInterface $arg_transaction);

    /**
     * Process the transaction.
     *
     * @param   sfTransactionInterface  $arg_transaction  The transaction object.
     *
     * @return  void
     */
    function process ();

  }