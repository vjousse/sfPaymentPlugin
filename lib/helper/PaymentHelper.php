<?php
/**
 * Payment Helper
 * Methods
 *
 * @package     sfPaymentPlugin
 * @category    Plugin
 * @author      Johnny Lattouf <johnny.lattouf@letscod.com>
 * @author      Antoine Leclercq <antoine.leclercq@letscod.com>
 * @version     $Revision$ changed by $Author$
 */

/**
 * Return the html of the form to process the payment
 *
 * @param Gateway $gateway
 * @return the html of the form to process the payment
 */
function payment_form_for($transaction) {
	$html  = "<form method=\"POST\" name=\"gateway_form\" ";
	$html .= "action=\"" . $transaction->getGatewayUrl() . "\">\n";

	foreach ($transaction->getFields() as $name => $value)
	{
		$html .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
	}

	return $html;
}