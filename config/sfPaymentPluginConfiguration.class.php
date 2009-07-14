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
     * @var string
     */
    private $_filename;

    /**
     * @var sfTransactionAdapterInterface
     */
    private $_transactionAdapter;

    /**
     * Plugin initialization.
     *
     * @return  void
     */
    public function initialize ()
    {
      $this->dispatcher->connect('transaction.prepare', array($this, 'onTransactionPrepare'));
      $this->dispatcher->connect('transaction.request', array($this, 'onTransactionRequest'));
      $this->dispatcher->connect('transaction.process', array($this, 'onTransactionProcess'));
    }

    /**
     * Get the transaction adapter.
     *
     * @return  sfTransactionAdapterInterface A transaction adapter implementation.
     */
    public function getTransactionAdapter ()
    {
      if (NULL === $this->_transactionAdapter)
      {
        $transactionAdapterClass   = sfConfig::get('app_transaction_adapter_class', 'sfTransactionAdapterMock');
        $browserClass              = sfConfig::get('app_transaction_browser_class', 'sfPaymentWebBrowser');
        $config                    = sfContext::getInstance()->getConfiguration();

        if ( ! class_exists($browserClass))
        {
          $this->_filename = sfConfig::get('sf_cache_dir') . '/sfPaymentPlugin/lib/sfPaymentWebBrowser.php';

          spl_autoload_register(array($this, 'autoloader'));

          if ( ! class_exists($browserClass) && class_exists('sfWebBrowser'))
          {
            $filesystem = new sfFilesystem($this->dispatcher);

            $filesystem->copy(sfConfig::get('sf_plugins_dir') . '/sfPaymentPlugin/data/generator/sfPaymentWebBrowser.php', $this->_filename);
            $filesystem->chmod(array($this->_filename), 0777);
          }
        }

        $this->_transactionAdapter = new $transactionAdapterClass(new $browserClass(), sfConfig::get('app_transaction_adapter_config', array()));
      }

      return $this->_transactionAdapter;
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
      $arg_event->setReturnValue($this->getTransactionAdapter()->prepare());
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
      $this->_assertTransactionEvent($arg_event);

      $arg_event->setReturnValue($this->getTransactionAdapter()->request($arg_event['transaction']));
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
      $this->_assertTransactionEvent($arg_event);

      $arg_event->setReturnValue($this->getTransactionAdapter()->process($arg_event['transaction']));
    }

    /**
     * Custom autoloader for cached version of sfPaymentWebBrowser class file.
     *
     * @param   string          $arg_name The name of the class or interface to
     *                                    load.
     *
     * @return  boolean|string  
     */
    public function autoloader ($arg_name)
    {
      sfContext::getInstance()->getLogger()->crit($arg_name);
      if ('sfPaymentWebBrowser' === $arg_name)
      {
        include $this->_filename;

        $result = class_exists($arg_name);
      }
      else
      {
        $result = FALSE;
      }

      return $result;
    }

    /**
     * Event listener for transaction.process event.
     *
     * @param   sfEvent                 $arg_event  Event object.
     *
     * @throws  sfTransactionException              In case no transaction implementation was passed along with the event.
     *
     * return   void
     */
    private function _assertTransactionEvent (sfEvent $arg_event)
    {
      if ( ! isset($arg_event['transaction']) || $arg_event['transaction'] instanceof rsfTransactionInterface)
      {
        throw new sfTransactionException('You should pass a rsfTransactionInterface implementation along with the event.');
      }
    }

  }