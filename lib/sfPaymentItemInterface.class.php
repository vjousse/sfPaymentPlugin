<?php

/**
 * sfPaymentGatewayInterface
 *
 * This interface provides the methods used for purchase's item
 *
 * @package     sfPaymentItemInterface
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

interface sfPaymentItemInterface
{

  public function getReference();

  public function getName();

  public function getQuantity();

  public function getPrice();

}
?>