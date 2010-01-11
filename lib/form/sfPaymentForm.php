<?php

  /**
   * sfPaymentForm.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentForm extends sfPaymentFormAbstract
  {

    /**
     * Process the payment selection.
     *
     * @return  sfPaymentTransactionInterface
     */
    public function process ()
    {
      throw new BadMethodCallException('Not yet implemented');
    }

  }