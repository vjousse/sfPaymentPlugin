<?php

/**
 * sfPaymentDoctrineStorageTable
 *
 * This class provides methods for retreive and store sfPaymentTransaction's instances.
 *
 * @package     sfPaymentDoctrineStorageTable
 * @category    Library
 * @author      Giuseppe Castelluzzo <g.castelluzzo@gmail.com>
 * @link        http://wiki.github.com/letscod/sfPaymentPlugin
 * @version     $Revision$ changed by $Author$
 */

class PluginsfPaymentDoctrineStorageTable extends Doctrine_Table implements sfPaymentStorageInterface
{
  public function getTransaction($reference) {
    $storage = $this->createQuery('t')->Where('t.reference = ?', $reference)->fetchOne();

    $transaction = new sfPaymentTransaction();
    $transaction->setGateway(unserialize($storage->getParams()));
    $transaction->setStorage($storage);

    return $transaction;
  }

  public function saveTransaction(sfPaymentTransaction $transaction) {
    $storage = new sfPaymentDoctrineStorage();
    $storage->setReference($transaction->getReference());
    $storage->setType($transaction->getType());
    $storage->setDate($transaction->getDate());
    $storage->setStatus($transaction->getStatus());
    $storage->setVendor($transaction->getVendor());
    $storage->setBuyer($transaction->getBuyer());
    $storage->setTotalAmount($transaction->getTotalAmount());
    $storage->setParams(serialize($transaction->getGateway()));

    $storage->save();
  }
}