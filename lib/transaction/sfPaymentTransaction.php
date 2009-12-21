<?php

  /**
   * Base class for sfPaymentTransactionInterface implementations.
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  class sfPaymentTransaction implements sfPaymentTransactionInterface
  {

    /**
     * @var integer The ammount to be paid.
     */
    protected $_amount;

    /**
     * @var string  The status of the transaction.
     */
    protected $_status;

    /**
     * @var string  The currency of the transaction
     */
    protected $_currency;

    /**
     * @var string  The description for the transaction.
     */
    protected $_description;

    /**
     * @var string  The transaction id.
     */
    protected $_transactionId;

    /**
     * Transaction construction.
     *
     * @return  void
     */
    public function __construct ()
    {
      $this->_status   = self::STATUS_UNKNOWN;
      $this->_currency = self::CURRENCY_EURO;
    }

    /**
     * Set the transaction id.
     *
     * @param   string  $arg_transactionId  The transaction id.
     *
     * @return  void
     */
    public function setTransactionId ($arg_transactionId)
    {
      $this->_transactionId = $arg_transactionId;
    }

    /**
     * Get the transaction id.
     *
     * @return  string  The transaction id.
     */
    public function getTransactionId ()
    {
      return $this->_transactionId;
    }

    /**
     * Get the amount for the transaction.
     *
     * @return  integer The amount for the transaction in cents.
     */
    public function getAmount ()
    {
      return $this->_amount;
    }

    /**
     * Set the amount for the transaction.
     *
     * @param   integer $arg_amount The amount for the transaction in cents.
     *
     * @return  void
     */
    public function setAmount ($arg_amount)
    {
      if ( ! is_int($arg_amount))
      {
        throw new InvalidArgumentException('The amount should be an integer value');
      }

      $this->_amount = $arg_amount;
    }

    /**
     * Get the status for the transaction.
     *
     * @return  string  The status for the transaction.
     */
    public function getStatus ()
    {
      return $this->_status;
    }

    /**
     * Set the status for the transaction.
     *
     * @param   string  $arg_status The status for the transaction.
     *
     * @return  void
     */
    public function setStatus ($arg_status)
    {
      switch ($arg_status)
      {
        case self::STATUS_APPROVED:
        case self::STATUS_DECLINED:
        case self::STATUS_PENDING :
        case self::STATUS_UNKNOWN :

          $this->_status = $arg_status;

        break;

        default:
          throw new InvalidArgumentException('Invalid value for status given');
      }
    }

    /**
     * Get the currency for the transaction.
     *
     * @return  string  The currency for the transaction.
     */
    public function getCurrency ()
    {
      return $this->_currency;
    }

    /**
     * Set the currency for the transaction.
     *
     * @param   string  $arg_currency The currency for the transaction.
     *
     * @return  void
     */
    public function setCurrency ($arg_currency)
    {
      switch ($arg_currency)
      {
        case self::CURRENCY_EURO:

          $this->_currency = $arg_currency;

        break;

        default:
          throw new InvalidArgumentException('Invalid value for currency given');
      }
    }

    /**
     * Get the description for the transaction.
     *
     * @return  string  The description for the transaction.
     */
    public function getDescription ()
    {
      return $this->_description;
    }

    /**
     * Set the description for the transaction.
     *
     * @param   string  $arg_description  The desctiption for the transaction.
     *
     * @return  void
     */
    public function setDescription ($arg_description)
    {
      if ( ! is_string($arg_description))
      {
        throw new InvalidArgumentException('Description should be a string value');
      }

      $this->_description = $arg_description;
    }

    /**
     * Check the amount requested.
     *
     * @param   integer $arg_amount The amount returned by mollie.
     *
     * @return  void
     */
    public function checkAmount ($arg_amount)
    {
      if ($this->_amount !== (int) $arg_amount)
      {
        throw new sfTransactionException(sprintf('Amount returned by gateway "%d" doesn\'t match requested amount "%d".', $arg_amount, $this->_amount));
      }
    }

    /**
     * Check the currency requested.
     *
     * @param   integer $arg_currency The amount currency by mollie.
     *
     * @return  void
     */
    public function checkCurrency ($arg_currency)
    {
      if ($this->_currency !== $arg_currency)
      {
        throw new sfTransactionException(sprintf('Currency returned by gateway "%s" doesn\'t match requested currency "%s".', $arg_currency, $this->_currency));
      }
    }

    /**
     * Cast the object to an XML notation.
     *
     * @return  DomDocument
     */
    public function toXmlElement ()
    {
      return sprintf("<transaction%s>\n  <!-- test -->\n</transaction>"
                    ,$this->_getTransactionNodeAttributes() ?: '');
    }

    /**
     * Create an object from an XML node.
     *
     * @param   SimpleXMLElement  $arg_xmlElement The XML representation.
     *
     * @return  void
     */
    public function fromXmlElement (SimpleXMLElement $arg_xmlElement)
    {
      $attributes = $arg_xmlElement->attributes;

      foreach ($attributes as $key => $value)
      {
        if (NULL !== $value)
        {
          $method = 'set' . ucfirst($key);

          $this->$method($value);
        }
      }
    }

    /**
     * Get the XML attributes for the transaction node.
     *
     * @return  string
     */
    private function _getTransactionNodeAttributes ()
    {
      $fields     = array('transactionId', 'status');
      $attributes = array();

      foreach ($fields as $fieldName)
      {
        $method = 'get' . ucfirst($fieldName);
        $value  = $this->$method();

        if (NULL !== $value)
        {
          $attributes[] = $fieldName . '="' . $value . '"';
        }
      }

      return implode(' ', $attributes);
    }

  }