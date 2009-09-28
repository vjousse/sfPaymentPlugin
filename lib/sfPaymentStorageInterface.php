<?php

/**
 * sfPaymentStorageInterface
 *
 * This interface provides methods for retreive and store sfPaymentTransaction's instances.
 *
 * @package     sfPaymentStorageInterface
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

interface sfPaymentStorageInterface
{

  public function getTransaction($type, $reference);

  public function saveTransaction(sfPaymentTransaction $transaction);

}
?>