<?php

  /**
   * sfPaymentFormAbstract.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  abstract class sfPaymentFormAbstract extends sfForm
  {

    /**
     * @var array The available gateways.
     */
    protected $_gateways;

    /**
     * Payment form constructor.
     *
     * @param   array                     $arg_gateways     The available gateways.
     * @param   string                    $arg_csrfSecret   The CSRF Secret.
     *
     * @return  void
     */
    public function __construct (array $arg_gateways, $arg_csrfSecret = NULL)
    {
      $this->setDefaults(array());

      $this->options   = array();
      $this->_gateways = array_filter($arg_gateways, array($this, '_filterGateway'));

      $this->widgetSchema = new sfWidgetFormSchema(array('transaction_id' => new sfWidgetFormInputHidden()
                                                        ,'gateway'        => new sfWidgetFormInputHidden()
                                                        ));

      $this->validatorSchema = new sfValidatorSchema(array('transaction_id' => new sfValidatorPass()
                                                          ,'gateway'        => new sfValidatorChoice(array('choices' => array_keys($this->_gateways)))
                                                          ));

      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

      $this->widgetSchema->setNameFormat('payment[%s]');
      $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('sf_payment_form');

      $this->setup();
      $this->configure();

      $this->addCSRFProtection($arg_csrfSecret);
      $this->resetFormFields();
    }

    /**
     * Process the form.
     *
     * @return  sfPaymentTransactionInterface
     */
    abstract public function process ();

    /**
     * Filter the gateways.
     *
     * @param   sfTransactionGatewayInterface $arg_gateway  A gateway implementation.
     *
     * @return  boolean
     */
    protected function _filterGateway (sfPaymentTransactionGatewayInterface $arg_gateway = NULL)
    {
      return NULL !== $arg_gateway && $arg_gateway->isEnabled();
    }

  }