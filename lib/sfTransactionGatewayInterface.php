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
     */
    function prepare ();

    /**
     * Request a transaction from the provider.
     */
    function request (sfTransactionInterface $arg_transaction);

    /**
     * Process the transaction.
     */
    function process ();

  }