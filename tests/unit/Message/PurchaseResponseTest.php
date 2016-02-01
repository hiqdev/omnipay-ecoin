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

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    private $request;

    private $purse          = 'ec12345';
    private $secret         = '12()&*&+_)?><';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $cancelUrl      = 'https://www.foodstore.com/failure';
    private $notifyUrl      = 'https://www.foodstore.com/notify';
    private $description    = 'Test Transaction long description';
    private $transactionId  = '12345ASD67890sd';
    private $amount         = '14.65';
    private $quantity       = '1';
    private $currency       = 'USD';
    private $testMode       = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'returnUrl'     => $this->returnUrl,
            'cancelUrl'     => $this->cancelUrl,
            'notifyUrl'     => $this->notifyUrl,
            'description'   => $this->description,
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testSuccess()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertStringStartsWith('https://ecoin.cc/account/mpay', $response->getRedirectUrl());
        $this->assertSame([
            'ECM_PURCH_DESC'    => $this->description,
            'ECM_INV_NO'        => $this->transactionId,
            'ECM_PAYEE_ID'      => $this->purse,
            'ECM_ITEM_COST'     => $this->amount,
            'ECM_QTY'           => $this->quantity,
            'ECM_RESULT_URL'    => $this->notifyUrl,
            'ECM_SUCCESS_URL'   => $this->returnUrl,
            'ECM_FAIL_URL'      => $this->cancelUrl,
        ], $response->getRedirectData());
    }
}
