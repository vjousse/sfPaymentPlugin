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
     * Form setup.
     *
     * @return  void
     */
    public function setup ()
    {
      $this->widgetSchema   ['gateway'] = new sfWidgetFormChoice(array('choices' => $this->_gateways));;
      $this->validatorSchema['gateway'] = new sfValidatorChoice(array('choices' => array_keys($this->_gateways)));
    }

    /**
     * Process the gateway selection.
     *
     * @return  sfPaymentTransactionInterface
     */
    public function process ()
    {
      if ( ! $this->isValid())
      {
        throw $this->errorSchema;
      }

      return $this->_gateways[$this->getValue('gateway')]
                  ->getTransaction();
    }

  }