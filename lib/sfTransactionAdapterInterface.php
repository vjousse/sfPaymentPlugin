<?php

  /**
   * Interface for handling transactions.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfTransactionAdapterInterface
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