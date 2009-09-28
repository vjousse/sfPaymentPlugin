<?php

/**
 * sfPaymentCartInterface
 *
 * This interface provides the methods used for cart
 * contains items (sfPaymentItemInterface)
 *
 * @package     sfPaymentCartInterface
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

interface sfPaymentCartInterface
{

  public function addItem(sfPaymentItemInterface $item);

  public function size();

  public function getItems();

  public function getTotalAmount();

}
?>