<?php

  /**
   * sfPaymentPlugin configuration.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentPluginConfiguration extends sfPluginConfiguration
  {

    /**
     * @var sfServiceContainerInterface
     */
    private $_serviceContainer;

    /**
     * Plugin setup.
     *
     * @uses    sfServiceContainerAutoloader
     *
     * @return  void
     */
    public function setup ()
    {
      if ( ! class_exists('sfServiceContainerAutoloader', FALSE))
      {
        require_once dirname(__FILE__) . '/../lib/vendor/symfony-components/DependencyInjection/sfServiceContainerAutoloader.php';

        sfServiceContainerAutoloader::register();
      }

      $this->dispatcher->connect('component.method_not_found', array($this, 'onComponentMethodNotFound'));
      $this->dispatcher->connect('user.method_not_found', array($this, 'onUserMethodNotFound'));
      $this->dispatcher->connect('transaction.prepare', array($this, 'onTransactionPrepare'));
      $this->dispatcher->connect('transaction.request', array($this, 'onTransactionRequest'));
      $this->dispatcher->connect('transaction.process', array($this, 'onTransactionProcess'));
    }

    /**
     * Hook for the user.method_not_found event.
     *
     * @param   sfEvent $arg_event  The event that caused invocation of the hook.
     *
     * @return  boolean             Indicator whether a hook was found or not.
     */
    public function onUserMethodNotFound (sfEvent $arg_event)
    {
      $result = FALSE;

      if ('getBasket' === $arg_event['method'])
      {
        $arg_event->setReturnValue($this->getServiceContainer()->getService('payment.basket'));

        $result = TRUE;
      }

      return $result;
    }

    /**
     * Hook for the component.method_not_found event.
     *
     * @param   sfEvent $arg_event  The event that caused invocation of the hook.
     *
     * @return  boolean             Indicator whether a hook was found or not.
     */
    public function onComponentMethodNotFound (sfEvent $arg_event)
    {
      $result = FALSE;

      if ('getServiceContainer' === $arg_event['method'])
      {
        $arg_event->setReturnValue($this->getServiceContainer());

        $result = TRUE;
      }

      return $result;
    }

    /**
     * Event listener for transaction.prepare event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionPrepare (sfEvent $arg_event)
    {
      $arg_event->setReturnValue($this->getTransactionGateway($arg_event['gateway'])
                                      ->prepare());
    }

    /**
     * Event listener for transaction.request event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionRequest (sfEvent $arg_event)
    {
      $this->_assertTransaction($arg_event);

      $arg_event->setReturnValue($this->getTransactionGateway($arg_event['gateway'])
                                      ->request($arg_event['transaction']));
    }

    /**
     * Event listener for transaction.process event.
     *
     * @param   sfEvent $arg_event  Event object.
     *
     * return   void
     */
    public function onTransactionProcess (sfEvent $arg_event)
    {
      $this->_assertTransaction($arg_event);

      $arg_event->setReturnValue($this->getTransactionGateway($arg_event['gateway'])
                                      ->process($arg_event['transaction']));
    }

    /**
     * Get the transaction gateway.
     *
     * @param   string                        $arg_gateway  The name of the gateway.
     *
     * @return  sfTransactionGatewayInterface
     */
    public function getTransactionGateway ($arg_gateway)
    {
      return $this->getServiceContainer()
                  ->getService('payment.gateway.' . $arg_gateway);
    }

    /**
     * Get the service container that describes the payment gateways.
     *
     * @return  sfServiceContainerInterface
     */
    public function getServiceContainer ()
    {
      if (NULL === $this->_serviceContainer)
      {
        $paths = array_merge(array($this->configuration->getRootDir() . '/config')
                            ,array_filter($this->configuration->getPluginPaths()
                                         ,array($this, '_isPaymentPlugin')));

        $this->_serviceContainer = $this->_loadServiceDescriptions($paths);
      }

      return $this->_serviceContainer;
    }

    /**
     * Assert if a transaction object was sent along with the event.
     *
     * @param   sfEvent                 $arg_event  Event object.
     *
     * @throws  sfTransactionException              In case no transaction implementation was passed along with the event.
     *
     * return   void
     */
    private function _assertTransaction (sfEvent $arg_event)
    {
      if ( ! isset($arg_event['transaction']) || ! $arg_event['transaction'] instanceof sfTransactionInterface)
      {
        throw new InvalidArgumentException('You should pass a sfTransactionInterface implementation along with the event.');
      }
    }

    /**
     * Assert if a gateway was sent along with the event.
     *
     * @param   sfEvent                 $arg_event  Event object.
     *
     * @throws  sfTransactionException              In case no transaction implementation was passed along with the event.
     *
     * return   void
     */
    private function _assertGateway (sfEvent $arg_event)
    {
      if ( ! isset($arg_event['gateway']) || ! is_string($arg_event['gateway']))
      {
        throw new InvalidArgumentException('You should pass a reference to a registered gateway along with the event.');
      }
    }

    /**
     * Load the service descriptions for the transaction gateways.
     *
     * @param   array                      $arg_paths
     *
     * @return  sfServiceContainerInterface
     */
    private function _loadServiceDescriptions (array $arg_paths)
    {
      $builder     = new sfServiceContainerBuilder();
      $loader      = new sfServiceContainerLoaderFileXml($builder, $arg_paths);
      $environment = method_exists($this->configuration, 'getEnvironment') ? $this->configuration->getEnvironment() : 'cli';

      $loader->load('payment_services_' . $environment . '.xml');

      return $builder;
    }

    /**
     * Check if a path leads to a payment plugin
     *
     * @param   string  $arg_path The path to the plugin.
     *
     * @return  boolean
     */
    private function _isPaymentPlugin ($arg_path)
    {
      return (bool) FALSE !== strpos($arg_path, 'sfPayment');
    }

  }
