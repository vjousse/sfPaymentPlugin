<?php

  /**
   * sfPaymentSelectForm.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentSelectForm extends sfPaymentFormAbstract
  {

    /**
     * Process the gateway selection.
     *
     * @return  sfPaymentTransactionInterface
     */
    public function process ()
    {
      throw new BadMethodCallException('Not yet implemented');
    }

  }