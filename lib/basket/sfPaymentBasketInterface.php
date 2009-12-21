<?php

  /**
   * sfPaymentBasket.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfPaymentBasketInterface extends sfSellable
  {

    /**
     * @var string
     */
    const DEFAULT_DESCRIPTION_FORMAT = 'Order %s';

    /**
     * Sets the sellable items of the basket.
     *
     * @param   sfSellable  $arg_sellable The object to add to the basket.
     *
     * @return  void
     *
     * @throws  InvalidArgumentException  When the array contains an item that
     *                                    does not implement the sfSellable
     *                                    interface.
     */
    function setSellables (array $arg_sellables);

    /**
     * Add a sellable to the list.
     *
     * @param   sfSellable  $arg_sellable The object to add to the basket.
     *
     * @return  integer                   The key for the sellable in the basket.
     */
    function addSellable (sfSellable $arg_sellable);

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
    function removeSellableAtKey ($arg_key);

  }