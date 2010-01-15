<?php

  /**
   * sfPaymentBasket.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentBasket implements sfPaymentBasketInterface
  {

    /**
     * @var string
     */
    private $_currency;

    /**
     * @var integer
     */
    private $_amount;

    /**
     * @var array
     */
    private $_sellables;

    /**
     * Basket construction.
     *
     * @param   array $arg_sellables  The sellables to insert into the basket.
     *
     * @return  void
     */
    public function __construct ($arg_currency, array $arg_sellables = array())
    {
      switch ($arg_currency)
      {
        case sfPaymentTransaction::CURRENCY_EURO:
        case sfPaymentTransaction::CURRENCY_UNITED_STATES_DOLLAR:

          $this->_currency = $arg_currency;
          break;

        default:
          throw new InvalidArgumentException("Invalid currency passed");
      }

      $this->setSellables($arg_sellables);
    }

    /**
     * Get the description for the transaction.
     *
     * @return  string  The description for the transaction.
     */
    public function getDescription ()
    {
      return 'basket';
    }

    /**
     * Get the total amount for the items in the basket. 
     *
     * @return  integer The amount for the transaction in cents.
     */
    public function getAmount ()
    {
      return $this->_amount;
    }

    /**
     * Get the currency for the transaction.
     *
     * @return  string  The currency for the transaction.
     */
    public function getCurrency ()
    {
      return $this->_currency;
    }

    /**
     * Sets the sellable items of the basket.
     *
     * @param   array $arg_sellables  The object to add to the basket.
     *
     * @return  void
     *
     * @throws  InvalidArgumentException  When the array contains an item that
     *                                    does not implement the sfSellable
     *                                    interface.
     */
    public function setSellables (array $arg_sellables)
    {
      $this->_sellables = array_filter($arg_sellables, array($this, '_filterSellable'));
    }

    /**
     * Add a sellable to the list.
     *
     * @param   sfPaymentSellable  $arg_sellable The object to add to the basket.
     *
     * @return  integer
     */
    public function addSellable (sfPaymentSellable $arg_sellable)
    {
      $this->_sellables[] = $arg_sellable;
    }

    /**
     * Remove a sellable from the basket at the specified key.
     *
     * @param   string                $arg_key  The key to search for.
     *
     * @return  void
     *
     * @throws  OutOfBoundsException  When no sellable is found in the basket
     *                                for the specified key.
     */
    public function removeSellableAtKey ($arg_key)
    {
      throw new BadMethodCallException("not yet implemented");
    }

    /**
     * Get an array representation for the Sellables
     *
     * @return  array
     */
    public function toArray ()
    {
      return $this->_sellables;
    }

    /**
     * Filter the sellables.
     *
     * @param   sfPaymentSellable $arg_sellable  A sellable implementation.
     *
     * @return  boolean
     */
    private function _filterSellable (sfPaymentSellable $arg_sellable = NULL)
    {
      $result = FALSE;

      if (NULL !== $arg_sellable)
      {
        if ($this->_currency !== $arg_sellable->getCurrency())
        {
          $arg_sellable->getAmountIn($this->_currency);

          return TRUE;
        }

        $result = TRUE;
      }

      return $result;
    }

    /**
     * Generate a string description for the basket.
     *
     * @see     getDescription
     *
     * @return  string
     */
    public function __toString ()
    {
      return $this->getDescription();
    }

  }