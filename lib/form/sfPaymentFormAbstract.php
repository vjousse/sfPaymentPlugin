<?php

  /**
   * sfPaymentFormAbstract.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision: 20173 $ changed by $Author: marijn $
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
     * @var array The sellable objects.
     */
    private $_sellables;

    /**
     * Payment form constructor.
     *
     * @param   array                   $arg_gateways     The available gateways.
     * @param   string                  $arg_csrfSecret   The CSRF Secret.
     *
     * @return  void
     */
    public function __construct (array $arg_gateways, $arg_csrfSecret = NULL)
    {
      $this->setDefaults(array());

      $this->options         = array();
      $this->_sellables      = array();
      $this->_gateways       = array_filter($arg_gateways, array($this, '_filterGateway'));
      $this->widgetSchema    = new sfWidgetFormSchema(array('gateway'        => new sfWidgetFormChoice(array('choices' => $this->_gateways))
                                                           ,'transaction_id' => new sfWidgetFormInputHidden()
                                                           ));
      $this->validatorSchema = new sfValidatorSchema(array('gateway'        => new sfValidatorChoice(array('choices' => array_keys($this->_gateways)))
                                                          ,'transaction_id' => new sfValidatorPass()
                                                          ));
      $this->errorSchema     = new sfValidatorErrorSchema($this->validatorSchema);

      $this->widgetSchema->setNameFormat('payment[%s]');

      $this->setup();
      $this->configure();

      $this->addCSRFProtection($arg_csrfSecret);
      $this->resetFormFields();
    }

    /**
     * Set the transaction object.
     *
     * @param   sfTransactionInterface  $arg_transaction  The transaction object to use.
     *
     * @return  sfPaymentFormAbstract                     The object itself to support a fluent interface.
     */
    public function setTransaction (sfTransactionInterface $arg_transaction)
    {
      $this->_transaction = $arg_transaction;

      //TODO: update relevant default values of the form

      return $this;
    }

    /**
     * Get the bound transaction object.
     *
     * @throws  BadMethodCallException  When no transaction object is associated with the form.
     *
     * @return  sfTransactionInterface
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
     * Check if the form has a transaction object attached to it.
     *
     * @return  boolean
     */
    public function hasTransaction ()
    {
      return NULL !== $this->_transaction;
    }

    /**
     * Set the transaction object.
     *
     * @return  sfPaymentFormAbstract                     The object itself to support a fluent interface.
     */
    public function setSellables (array $arg_sellables)
    {
      $this->_sellables = array_filter($arg_sellables, array($this, '_filterSellable'));

      //TODO: update relevant default values of the form

      return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws  BadMethodCallException  If no transaction was set for the form.
     */
    public function bind (array $taintedValues = NULL, array $taintedFiles = NULL)
    {
      if (NULL === $this->_transaction)
      {
        throw new BadMethodCallException(sprintf('Cannot bind the "%s" form to the request because no transaction object was set.', get_class($this)));
      }

      parent::bind($taintedValues, $taintedFiles);
    }

    /**
     * Filter the gateways.
     *
     * @param   sfTransactionGatewayInterface $arg_gateway  A gateway implementation.
     *
     * @return  boolean
     */
    private function _filterGateway (sfTransactionGatewayInterface $arg_gateway = NULL)
    {
      return NULL !== $arg_gateway;
    }

    /**
     * Filter the sellables.
     *
     * @param   sfSellable $arg_sellable  A sellable implementation.
     *
     * @return  boolean
     */
    private function _filterSellable (sfSellable $arg_sellable = NULL)
    {
      return NULL !== $arg_sellable;
    }

  }