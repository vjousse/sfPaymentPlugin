<?php
/**
 * sfPaymentPlugin configuration.
 * 
 * @package   sfPaymentPlugin
 * @category  Library
 * @author    Marijn Huizendveld <marijn@round84.com>
 *
 * @version   $Revision: 19692 $ changed by $Author: marijn $
 */

include dirname(__FILE__).'/../bootstrap/unit.php';

$h = new lime_harness(new lime_output_color());
$h->register(sfFinder::type('file')->name('*Test.php')->in(dirname(__FILE__).'/..'));

exit($h->run() ? 0 : 1);
