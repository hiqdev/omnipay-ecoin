<?php

/*
 * eCoin driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-ecoin
 * @package   omnipay-ecoin
 * @license   MIT
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\eCoin;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway for eCoin.
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'eCoin';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'purse'     => '',
            'secret'    => '',
            'testMode'  => false,
        ];
    }

    /**
     * Get the unified purse.
     *
     * @return string merchant purse
     */
    public function getPurse()
    {
        return $this->getParameter('purse');
    }

    /**
     * Set the unified purse.
     *
     * @param string $value merchant purse
     *
     * @return self
     */
    public function setPurse($value)
    {
        return $this->setParameter('purse', $value);
    }

    /**
     * Get the unified secret key.
     *
     * @return string secret key
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * Set the unified secret key.
     *
     * @param string $value secret key
     *
     * @return self
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\eCoin\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\eCoin\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\eCoin\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\eCoin\Message\CompletePurchaseRequest', $parameters);
    }
}
