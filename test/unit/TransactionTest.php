<?php
require_once dirname(__FILE__).'/../bootstrap/unit.php';
require_once sfConfig::get("sf_plugins_dir").'/sfPaymentPlugin/lib/sfPaymentMockGatewayInterface.php';

$t = new lime_test(8, new lime_output_color());

$gateway = new sfPaymentMockGatewayInterface();
$transaction = new sfPaymentTransaction($gateway);

$t->comment('sfPaymentGatewayInterface.');
$t->is($transaction->getVendor(),"", "vendor not set yet");
$transaction->setVendor("test");
$t->is($transaction->getVendor(),"test", "::setVendor");
$t->is($transaction->getCurrency(),"", "currency not set yet");
$transaction->setCurrency("USD");
$t->is($transaction->getCurrency(),"USD", "::setCurrency");
$t->is($transaction->getAmount(),"", "amount not set yet");
$transaction->setAmount(100);
$t->is($transaction->getAmount(),100, "::setAmount");
$t->is($transaction->getProductName(),"", "product name not set yet");
$transaction->setProductName("Symfony Book");
$t->is($transaction->getProductName(),"Symfony Book", "::setProductName");