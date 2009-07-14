# sfPaymentPlugin

## Requirements

* Symfony 1.2

## Optional

* [`sfWebBrowserPlugin`](http://www.symfony-project.org/plugins/sfWebBrowserPlugin "Visit the plugin page for the sfWebBrowserPlugin")

## Installation

During initial development no package file is available. You can install the 
plugin by either downloading the source or adding an `svn:externals` property to
your working copy.

**Download**:

    $ svn checkout http://svn.symfony-project.com/sfPaymentPlugin/branches/1.2-marijn

**SVN**:

    $ svn propset externals sfPaymentPlugin http://svn.symfony-project.com/sfPaymentPlugin/branches/1.2-marijn

## Usage

This plugin acts as a base plugin for different payment system implementations:

* `sfPaymentIdealMolliePlugin`

### Configuration

The following configuration values are available in `app.yml`:

    [yml]
    all:
      transaction:
        adapter_class: 'sfTransactionAdapterMock'
        browser_class: 'sfPaymentWebBrowser'

Because of the usage of the event system the plugin is highly customizable.
Besides listening for the events that are used for preparing, requesting and
processing the transactions you can also use a more lightweight custom browser
implementation in favor of the default `sfWebBrowserPlugin`. Just make sure your
class implements the `sfWebBrowserInterface`. The default `sfPaymentWebBrowser`
class is just a type extension of the default `sfWebBrowser` class from the
`sfWebBrowserPlugin` that implements the `sfWebBrowserInterface`. In case you 
don't define it yourself it is created by the plugin for you, within the cache
directory of your project.

### Events

1. _`transaction.prepare`_

    The `transaction.prepare` event makes a request to the payment implementation
    and prepares a transaction on the server. Often this will result in a response
    to the user who has to input some data about the account used.

        [php]
        $response = $eventDispatcher->notify(new sfEvent($this, 'transaction.prepare'));

1. _`transaction.request`_

    The `transaction.request` event is send when a transaction object was created
    containing the specification of the payment. It is passed allong to the 
    event dispatcher.

        [php]
        $transaction = new sfTransaction();
        
        // ... add the content to the transaction object about the payment.
        
        $response = $eventDispatcher->notify(new sfEvent($this, 'transaction.request', array('transaction' => $transaction)));

        // if we don`t use a database to store our transaction objects save to the user.
        $user->setTransaction($transaction);

1. _`transaction.process`_

    The `transaction.process` event is send after the request was made to the
    payment server. This will check if payment was succesfull.

        [php]
        // if we don`t use a database to store our transaction objects get from the user.
        $transaction = $user->getTransaction();

        $response = $eventDispatcher->notify(new sfEvent($this, 'transaction.process', array('transaction' => $transaction)));