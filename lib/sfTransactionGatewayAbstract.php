<?php

  /**
   * sfTransactionGatewayAbstract
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision: 24813 $ changed by $Author: marijn $
   */
  abstract class sfTransactionGatewayAbstract implements sfTransactionGatewayInterface
  {

    /**
     * Generate a string representation for the gateway object.
     *
     * @return  String
     */
    public function __toString ()
    {
      return get_class($this);
    }

  }