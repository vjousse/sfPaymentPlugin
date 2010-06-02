sfPaymentPlugin 
===============

**WARNING : This plugin is in alpha state, therefore NOT READY for production.
It still needs contribution to be fully functional.** 

The `sfPaymentPlugin` intends to bring a standard, flexible and maintainable solution 
for managing online payments using the symfony framework throughout a set of plugins.

Please visit the [official plugin page](http://wiki.github.com/letscod/sfPaymentPlugin "sfPaymentPlugin on github") of the sfPayment project for more information.

**Note** : The only provider available as of this first release day is PayPal in standard mode.
Please check [sfPaymentPayPalPlugin](http://www.symfony-project.org/plugins/sfPaymentPayPalPlugin "sfPaymentPayPalPlugin : implementing PayPal payments on symfony") for more information on PayPal support.
 
Installation
------------

  * Install the plugin

        $ symfony plugin:install sfPaymentPlugin --stability=alpha

  * Clear you cache

        $ symfony cc

Wording
-------
Yes, it's always easier when we understand each others.

  * **Gateway** :
  It consists of the payment gateway (PayPal, Paybox, Google Checkout, Amazon Payments, 2CheckOut, ...). Each gateway object extends the sfPaymentGatewayInterface interface (see sfPaymentPlugin/lib/sfPaymentGatewayInterface.class.php). As of today, only PayPal is supported.
  
  * **Transaction** :
  The transaction deals with the money exchange and sends the required information to the gateway.
  
  * **sfPayment plugins** :
  As discussed on the mailing-lists (see the [official plugin page](http://wiki.github.com/letscod/sfPaymentPlugin "sfPaymentPlugin on github")), each gateway support files will be stored in a specific plugin. The **sfPayment plugins** (or **sfPayment plugin suite**) consist of all plugins related to sfPayment (sfPaymentPlugin, sfPaymentPayPalPlugin, sfPaymentPayboxPlugin, sfPaymentGoogleCheckoutPlugin...). The goal is to ease the development of gateway support. It still needs to be proven productive for developers, we may switch to integrating all gateway support in a single plugin. Any advice on this is welcome.

How to use it
-------------

This plugin is required to use any sfPayment plugins (sfPaymentPayPal, sfPaymentPaybox, sfPayment[gateway]...). As explained in the introduction, the only supported gateway is PayPal as of today. It brings class and modules structure to ease implementation of online payment.

It contains mainly 2 classes :

  * **sfPaymentTransaction** : the transaction class, the developer implementing sfPayment should only be using objects of this class.
  * **sfPaymentGatewayInterface** : the gateway (or provider) configuration class, a gateway object is injected in the transaction class. 

It contains as well an helper, basic unit test and a mock class. 

### Create the payment button

The following code creates a simple transaction using the PayPal gateway interface.
It is located of the actions.class.php of your payment module. 

          #app/modules/payment/actions.class.php
          
          // create paypal library instance
          $gateway = new sfPaymentPayPal();
          
          // instanciate transaction
          $this->transaction = new sfPaymentTransaction($gateway);
          
          // enable test mode if needed
          $this->transaction->enableTestMode();
          
          // define transaction information :
          
          // - currency
          $this->transaction->setCurrency("USD");
          
          // - product information
          $this->transaction->setAmount('20');
          $this->transaction->setProductName('a $20 symfony book, deal !');

The following code creates the now famous "Pay with PayPal" button.
It uses the Payment helper which brings the *payment_form_tag_for* function that will create the form tag with the hidden parameters.

          <?php use_helper('Payment'); ?>
          
          <h1>Sample : Pay with PayPal</h1>
                    
          <?php echo payment_form_tag_for($transaction->getGateway()); ?>
            <input type="submit" value="Pay with PayPal">
          </form>

The generated HTML looks like the following.

          <h1>Sample : Pay with PayPal</h1>                    
          <form method="POST" name="gateway_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
            <input type="hidden" name="rm" value="2"/>
            <input type="hidden" name="cmd" value="_xclick"/>
            <input type="hidden" name="return" value="http://localhost/sfPaymentPayPal/success"/>
            <input type="hidden" name="cancel_return" value="http://localhost/sfPaymentPayPal/failure"/>
            <input type="hidden" name="notify_url" value="http://localhost/sfPaymentPayPal/ipn"/>
            <input type="hidden" name="business" value="seller_1248418954_biz@letscod.com"/>
            <input type="hidden" name="currency_code" value="USD"/>
            <input type="hidden" name="amount" value="20"/>
            <input type="hidden" name="item_name" value="a $20 symfony book, deal !"/>
            <input type="submit" value="Pay with PayPal">
          </form>

Clicking on the "Pay with PayPal" link will bring you to the PayPal payment page.

![PayPal Payment page sample for sfPaymentPlugin](http://farm3.static.flickr.com/2557/3752095913_498538c543.jpg?v=0 "PayPal Payment page sample for sfPaymentPlugin")

### Validating the IPN

The IPN validation is implemented in BasesfPaymentPayPalActions.class.php located in the sfPaymentPayPal module's lib.

The IPN consists of a verification with the gateway that a payment was processed. 
In the sfPaymentPayPalPlugin, it is processed at 2 specific times :

  * When the payment is made, PayPal posts the IPN values to the *notify_url* (here http://localhost/sfPaymentPayPal/ipn). In the case where the project is hosted locally, surely PayPal won't be able to access to your web server. It works fine when the URL is public.
  * Once the user returns to the store after paying (he clicks on the *Return to XXX's Test Store* button), he sends the IPN data to the *return* url (sfPaymentPayPal/success in our case). The success action execute the IPN check which works fine if your Web server is connected to the Internet.

Finally, all you have to do is define the methods related to each payment status in the action.

        #sfPaymentPayPalPlugin/modules/sfPaymentPayPal/actions/actions.class.php
        
        /**
         * Transaction verified and completed
         *
         * @param array $post_parameters
         */
        public function transactionCompleted(sfWebRequest $request)
        {
          
        }
        
        /**
         * Transaction verified and failed
         *
         * @param array $post_parameters
         */
        public function transactionFailed(sfWebRequest $request)
        {
          
        }
        
        /**
         * Transaction invalid (not verified)
         *
         * @param array $post_parameters
         */
        public function transactionInvalid(sfWebRequest $request)
        {
          
        } 

**IMPORTANT** : The sfPaymentPlugin does not (yet?) validate that the transaction information matches the IPN returns.
It is the responsability of the developer to implement the check. 

### Done

That's it !

The payment is made and we were able to validate with the gateway that the IPN information are correct.

The gateway was injected in the transaction object. We only used the transaction standard methods as well as pseudo-behaviors action classes.

Feedback
--------

Please provide feedbacks, comments, support on the [symfony-payment-developers Google Group](http://groups.google.com/group/symfony-payment-developers "The symfony-payment-developers Google Group") 

             
TODO
----
  * Get feedbacks for this first version and improve plugin consistency
  * Write gateway plugin developer documentation
  * Integrate support for server to server transactions
  * Implement more gateways / providers (Google CheckOut, Amazon Payments...)
  * When a transaction is submitted and the IPN validated, the information need to be checked against the initial transaction (to secure against values hacking). This asks the question of the data storage. It it the role of this plugin ?