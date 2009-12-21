<?php

  /**
   * sfPaymentSellable.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfPaymentSellable
  {

    /**
     * Get the currency.
     *
     * @return  string
     */
    function getCurrency ();

    /**
     * Get the amount.
     *
     * @return  integer
     */
    function getAmount ();

    /**
     * Get the description for the transaction.
     *
     * @return  string  The description for the transaction.
     */
    function getDescription ();

  }