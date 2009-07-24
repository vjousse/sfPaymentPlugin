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
function payment_form_tag_for($gateway) {
	$html  = "<form method=\"POST\" name=\"gateway_form\" ";
	$html .= "action=\"" . $gateway->gatewayUrl . "\">\n";

	foreach ($gateway->fields as $name => $value)
	{
		$html .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
	}

	return $html;
}