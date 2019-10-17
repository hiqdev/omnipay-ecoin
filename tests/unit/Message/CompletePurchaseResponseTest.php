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

class CompletePurchaseResponseTest extends TestCase
{
    private $request;

    private $purse                  = 'ec12345';
    private $secret                 = '22SAD#-78G8sdf$88';
    private $hash                   = 'fbbc3a665048e3f4aa001dd212b82588';
    private $description            = 'Test Transaction long description';
    private $transactionId          = '1SD672345A890sd';
    private $transactionReference   = 'sdfa1SD672345A8';
    private $timestamp              = '1454331086';
    private $payer                  = 'ec54321';
    private $amount                 = '1465.01';
    private $currency               = 'USD';
    private $testMode               = false;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'     => $this->purse,
            'secret'    => $this->secret,
            'testMode'  => $this->testMode,
        ]);
    }

    public function testInvalidHashException()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidResponseException::class);
        $this->expectExceptionMessage('Invalid hash');
        new CompletePurchaseResponse($this->request, [
            'description'           => $this->description,
        ]);
    }

    public function testSuccess()
    {
        $response = new CompletePurchaseResponse($this->request, [
            'ECM_HASH'                  => $this->hash,
            'ECM_PURCH_DESC'            => $this->description,
            'ECM_PAYER_ID'              => $this->payer,
            'ECM_PAYEE_ID'              => $this->purse,
            'ECM_PAYMENT_AMOUNT'        => $this->amount,
            'ECM_ITEM_COST'             => $this->amount,
            'ECM_TRANS_DATE'            => $this->timestamp,
            'ECM_INV_NO'                => $this->transactionId,
            'ECM_TRANS_ID'              => $this->transactionReference,
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->getTestMode());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertSame($this->transactionId,         $response->getTransactionId());
        $this->assertSame($this->transactionReference,  $response->getTransactionReference());
        $this->assertSame($this->amount,                $response->getAmount());
        $this->assertSame($this->payer,                 $response->getPayer());
        $this->assertSame($this->hash,                  $response->getHash());
        $this->assertSame($this->currency,              $response->getCurrency());
        $this->assertSame('2016-02-01T12:51:26+00:00',  $response->getTime());
    }
}
