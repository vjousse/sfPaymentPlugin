<?php

/**
 * sfPaymentGatewayInterface
 *
 * This interface provides the methods used for cart
 * contains items (sfPaymentItemInterface)
 *
 * @package     sfPaymentCartInterface
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

interface sfPaymentCartInterface
{

  public function add($item);

  public function delete($item);

  public function size();

  public function getItems();

  public function getTotalPrice();

}
?>