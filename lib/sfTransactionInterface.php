<?php

  /**
   * Interface for general transactions.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfTransactionInterface
  {

    /**
     * @const string  The string that indicates that a transaction has been
     *                aprroved.
     */
    const STATUS_APPROVED = 'approved';

    /**
     * @const string  The string that indicates that a transaction has been
     *                declined.
     */
    const STATUS_DECLINED = 'declined';

    /**
     * @const string  The string that indicates that a transaction has been
     *                requested but that a response is pending.
     */
    const STATUS_PENDING = 'pending';

    /**
     * @const string  The string that indicates that the transaction has not yet
     *                requested.
     */
    const STATUS_UNKNOWN = 'unknown';

    /**
     * @const string  The euro currency.
     */
    const CURRENCY_EURO = 'EUR';

    /**
     * @const string  The US dollar currency.
     */
    const CURRENCY_UNITED_STATES_DOLLAR = 'USD';

    /**
     * Set the transaction id.
     *
     * @param   string  $arg_transactionId  The transaction id.
     *
     * @return  void
     */
    function setTransactionId ($arg_transactionId);

    /**
     * Get the transaction id.
     *
     * @return  string  The transaction id.
     */
    function getTransactionId ();

    /**
     * Get the amount for the transaction.
     *
     * @return  integer The amount for the transaction in cents.
     */
    function getAmount ();

    /**
     * Set the amount for the transaction.
     *
     * @param   integer $arg_amount The amount for the transaction in cents.
     *
     * @return  void
     */
    function setAmount ($arg_amount);

    /**
     * Get the status for the transaction.
     *
     * @return  string  The status for the transaction.
     */
    function getStatus ();

    /**
     * Set the status for the transaction.
     *
     * @param   string  $arg_status The status for the transaction.
     *
     * @return  void
     */
    function setStatus ($arg_status);

    /**
     * Get the currency for the transaction.
     *
     * @return  string  The currency for the transaction.
     */
    function getCurrency ();

    /**
     * Set the currency for the transaction.
     *
     * @param   string  $arg_currency The currency for the transaction.
     *
     * @return  void
     */
    function setCurrency ($arg_currency);

    /**
     * Get the description for the transaction.
     *
     * @return  string  The description for the transaction.
     */
    function getDescription ();

    /**
     * Set the description for the transaction.
     *
     * @param   string  $arg_description  The desctiption for the transaction.
     *
     * @return  void
     */
    function setDescription ($arg_description);

    /**
     * Check the amount requested.
     *
     * @param   integer $arg_amount The amount returned by mollie.
     *
     * @return  void
     */
    function checkAmount ($arg_amount);

    /**
     * Check the currency requested.
     *
     * @param   integer $arg_currency The amount currency by mollie.
     *
     * @return  void
     */
    function checkCurrency ($arg_currency);

  }