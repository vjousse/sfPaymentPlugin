<?php

  /**
   * sfSellable.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision: 24815 $ changed by $Author: marijn $
   */
  interface sfSellable
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

  }