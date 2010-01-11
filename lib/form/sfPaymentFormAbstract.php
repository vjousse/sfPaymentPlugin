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
     * @var sfTransactionInterface  The transaction object bound to the form.
     */
    private $_transaction;

    /**
     * @var array The available gateways.
     */
    private $_gateways;

    /**
     * @var sfPaymentBasketInterface  The basket containing all the objects on sale.
     */
    private $_basket;

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
                                                        ,'gateway'        => new sfWidgetFormChoice(array('choices' => $this->_gateways))
                                                        ,'selection'      => new sfWidgetFormChoice(array('expanded' => TRUE
                                                                                                         ,'multiple' => TRUE
                                                                                                         ,'choices'  => NULL))
                                                        ));

      $this->validatorSchema = new sfValidatorSchema(array('transaction_id' => new sfValidatorPass()
                                                          ,'gateway'        => new sfValidatorChoice(array('choices' => array_keys($this->_gateways)))
                                                          ,'selection'      => new sfValidatorChoice(array('choices' => NULL))
                                                          ));

      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

      $this->widgetSchema->setNameFormat('payment[%s]');

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
     * {@inheritdoc}
     *
     * @throws  BadMethodCallException  If no transaction was set for the form.
     * @throws  BadMethodCallException  If no basket was set for the form.
     */
    public function bind (array $taintedValues = NULL, array $taintedFiles = NULL)
    {
      if (NULL === $this->_transaction)
      {
        throw new BadMethodCallException(sprintf('Cannot bind the "%s" form to the request because no transaction object was set', get_class($this)));
      }
      else if (NULL === $this->_basket)
      {
        throw new BadMethodCallException(sprintf('Cannot bind the "%s" form to the request because no basket object was set', get_class($this)));
      }

      parent::bind($taintedValues, $taintedFiles);
    }

    /**
     * Call this method to update the form defaults to reflect changes in the
     * bound transaction and basket objects.
     *
     * @return  sfPaymentFormAbstract                     The object itself to support a fluent interface.
     */
    public function updateFormDefaults ()
    {
      $defaults = array();

      if ($this->hasBasket())
      {
        $defaults['selection'] = $this->_updateChoiceField($this->widgetSchema['selection'], $this->getBasket()->toArray());
      }

      $this->setDefaults($defaults);

      return $this;
    }

    /**
     * Set the transaction object.
     *
     * @param   sfTransactionInterface  $arg_transaction  The transaction object
     *                                                    to use
     *
     * @return  sfPaymentFormAbstract                     The object itself to
     *                                                    support a fluent
     *                                                    interface
     */
    public function setTransaction (sfPaymentTransactionInterface $arg_transaction)
    {
      if ($this->hasTransaction())
      {
        throw new BadMethodCallException(sprintf('Cannot set the transaction object on this "%s" form instance because one is already set.', get_class($this)));
      }

      $this->_transaction = $arg_transaction;

      return $this->updateFormDefaults();
    }

    /**
     * Set the basket for the form.
     *
     * @param   sfPaymentBasketInterface  $arg_gateways     The available gateways.
     *
     * @return  sfPaymentFormAbstract                     The object itself to support a fluent interface.
     */
    public function setBasket (sfPaymentBasketInterface $arg_basket)
    {
      if ($this->hasBasket())
      {
        throw new BadMethodCallException(sprintf('Cannot set the basket object on this "%s" form instance because one is already set.', get_class($this)));
      }

      $this->_basket = $arg_basket;

      return $this->updateFormDefaults();
    }

    /**
     * Get the bound transaction object.
     *
     * @throws  BadMethodCallException  When no transaction object is associated with the form.
     *
     * @return  sfPaymentTransactionInterface
     */
    public function getTransaction ()
    {
      if ( ! $this->hasTransaction())
      {
        throw new BadMethodCallException(sprintf('Cannot get transaction object because there is no object bound to this "$s" form instance.', get_class($this)));
      }

      return $this->_transaction;
    }

    /**
     * Get the bound basket object.
     *
     * @throws  BadMethodCallException  When no basket object is associated with the form.
     *
     * @return  sfPaymentBasketInterface
     */
    public function getBasket ()
    {
      if ( ! $this->hasBasket())
      {
        throw new BadMethodCallException(sprintf('Cannot get basket object because there is no object bound to this "$s" form instance.', get_class($this)));
      }

      return $this->_basket;
    }

    /**
     * Check if the form has a transaction object attached to it.
     *
     * @return  boolean
     */
    public function hasTransaction ()
    {
      return NULL !== $this->_transaction;
    }

    /**
     * Check if the form has a basket object attached to it.
     *
     * @return  boolean
     */
    public function hasBasket ()
    {
      return NULL !== $this->_basket;
    }

    /**
     * Update a choice field.
     *
     * @param   sfWidgetFormChoice  The widget to update
     *
     * @return  array               The keys of the fields that were added
     */
    private function _updateChoiceField (sfWidgetFormChoice $arg_widget, array $arg_choices)
    {
      $arg_widget->setOption('choices', $arg_choices);

      return array_keys($arg_options);
    }


    /**
     * Filter the gateways.
     *
     * @param   sfTransactionGatewayInterface $arg_gateway  A gateway implementation.
     *
     * @return  boolean
     */
    private function _filterGateway (sfPaymentTransactionGatewayInterface $arg_gateway = NULL)
    {
      return NULL !== $arg_gateway && $arg_gateway->isEnabled();
    }

  }