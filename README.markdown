# sfPaymentPlugin

## Requirements

* Symfony 1.2
* sfWebBrowserPlugin

## Installation

During initial development no package file is available. You can install the 
plugin by either downloading the source or adding an `svn:externals` property to
your working copy.

Download:
    $ svn checkout http://svn.symfony-project.com/sfPaymentPlugin/branches/1.2-marijn

SVN:
    $ svn propset externals sfPaymentPlugin http://svn.symfony-project.com/sfPaymentPlugin/branches/1.2-marijn

## Usage

This plugin acts as a base plugin for different payment system implementations.

### Configuration

The following configuration values are available.

    [yml]
    all:
      transaction:
        adapter_class: 'sfTransactionAdapterMock'
        browser_class: 'sfWebBrowserMock'

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
