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
     * @var sfTransactionInterface  The transaction object bound to the form.
     */
    protected $_transaction;

    /**
     * @var sfPaymentBasketInterface  The basket containing all the objects on sale.
     */
    protected $_basket;

    /**
     * Form setup.
     *
     * @return  void
     */
    public function setup ()
    {
      $this->widgetSchema   ['selection'] = new sfWidgetFormChoice(array('expanded' => TRUE
                                                                        ,'multiple' => TRUE
                                                                        ,'choices'  => NULL));
      $this->validatorSchema['selection'] = new sfValidatorChoice(array('choices' => NULL));
    }

    /**
     * Process the payment selection.
     *
     * @return  sfPaymentTransactionInterface
     */
    public function process ()
    {
      throw new BadMethodCallException('Not yet implemented');
    }

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
     * @return  sfPaymentForm The object itself to support a fluent interface.
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
     * @return  sfPaymentForm                             The object itself to
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
     * @param   sfPaymentBasketInterface  $arg_gateways The available gateways
     *
     * @return  sfPaymentForm                           The object itself to
     *                                                  support a fluent
     *                                                  interface
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
     * @throws  BadMethodCallException  When no transaction object is
     *                                  associated with the form
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
     * @throws  BadMethodCallException    When no basket object is associated
     *                                    with the form
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
    protected function _updateChoiceField (sfWidgetFormChoice $arg_widget, array $arg_choices)
    {
      $arg_widget->setOption('choices', $arg_choices);

      return array_keys($arg_options);
    }

  }