<?php

/*
 * eCoin driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-ecoin
 * @package   omnipay-ecoin
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\eCoin\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * eCoin Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data;

        if ($this->getHash() !== $this->calculateHash()) {
            throw new InvalidResponseException('Invalid hash');
        }
    }

    /**
     * Whether the payment is successful.
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Whether the payment is test.
     * XXX TODO.
     * @return boolean
     */
    public function getTestMode()
    {
        return (bool) $this->data['TEST_VAR_TO_BE_SET'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['ECM_INV_NO'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data['ECM_TRANS_ID'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getAmount()
    {
        return $this->data['ECM_ITEM_COST'];
    }

    /**
     * Returns the currency.
     * @return string
     */
    public function getCurrency()
    {
        return 'USD';
    }

    /**
     * Returns the payer ID.
     * @return string
     */
    public function getPayer()
    {
        return $this->data['ECM_PAYER_ID'];
    }

    /**
     * Returns the payment date.
     * @return string
     */
    public function getTime()
    {
        return date('c', $this->data['ECM_TRANS_DATE']);
    }

    /**
     * Get hash from request.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->data['ECM_HASH'];
    }

    /**
     * Calculate hash to validate incoming confirmation.
     *
     * @return string
     */
    public function calculateHash()
    {
        $str =  $this->data['ECM_TRANS_ID'] .
                $this->data['ECM_TRANS_DATE'] .
                $this->request->getPurse() .
                $this->data['ECM_PAYER_ID'] .
                $this->data['ECM_ITEM_COST'] .
                $this->data['ECM_QTY'] .
                $this->request->getSecret();

        return md5($str);
    }
}
