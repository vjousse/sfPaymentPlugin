<?php

  /**
   * sfPaymentRoute.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentRoute extends sfObjectRoute
  {

    /**
     * Create a sfPaymentRoute object.
     *
     * {@inheritDoc}
     *
     * @return  void
     */
    public function __construct($pattern, array $defaults = array(), array $requirements = array(), array $options = array())
    {
      $requirements = array_merge(array('sf_method'      => array("get", "post")
                                       ,'transaction_id' => "\d+"
                                       )
                                 ,$requirements);
      $options      = array_merge(array('model' => 'sfPaymentTransaction')
                                 ,$options
                                 ,array('type'   => 'object'
                                       ,'method' => 'getById'
                                       ));

      parent::__construct($pattern, $defaults, $requirements, $options);
    }

    /**
     * Get the object for the parameters. If no object was found an new one is
     * instantiated.
     *
     * @param   array $parameters The parameters for the query.
     *
     * @return  sfTransactionInterface.
     */
    protected function getObjectForParameters ($parameters)
    {
      $className = $this->options['model'];

      if ( ! isset($parameters['transaction_id']))
      {
        $transaction = new $className();
      }
      else
      {
        $transaction = new $className();
      }

      return $transaction;
    }

    /**
     * Convert the object to an array of paramters.
     *
     * @param   sfTransactionInterface  $object The object to convert.
     *
     * @return  array
     */
    protected function doConvertObjectToArray($object)
    {
      if ( ! $object instanceof sfTransactionInterface)
      {
        throw new InvalidArgumentException(sprintf('The "%s" instance does not implement the sfTransactionInterface and could therefor not be used with the "%s" routing.', get_class($object), get_class($this)));
      }

      return array('transaction_id' => $object->getTransactionId());
    }

  }