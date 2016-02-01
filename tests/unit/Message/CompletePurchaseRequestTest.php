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
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    private $purse                  = 'ec12345';
    private $secret                 = '*&^^&$%&(23';
    private $hash                   = '33bfff79d7eeffdca9a9ac7b34a78dfc';
    private $description            = 'Test Transaction long description';
    private $transactionId          = '12345ASD67890sd';
    private $transactionReference   = '12345678';
    private $timestamp              = '1454331086';
    private $payer                  = 'ec54321';
    private $amount                 = '1465.01';
    private $testMode               = false;

    public function setUp()
    {
        parent::setUp();

        $httpRequest = new HttpRequest([], [
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

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $httpRequest);
        $this->request->initialize([
            'purse'     => $this->purse,
            'secret'    => $this->secret,
            'testMode'  => $this->testMode,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->description,   $data['ECM_PURCH_DESC']);
        $this->assertSame($this->transactionId, $data['ECM_INV_NO']);
        $this->assertSame($this->hash,          $data['ECM_HASH']);
        $this->assertSame($this->amount,        $data['ECM_PAYMENT_AMOUNT']);
        $this->assertSame($this->timestamp,     $data['ECM_TRANS_DATE']);
        $this->assertSame($this->payer,         $data['ECM_PAYER_ID']);
        $this->assertSame($this->purse,         $data['ECM_PAYEE_ID']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertInstanceOf('Omnipay\eCoin\Message\CompletePurchaseResponse', $response);
    }
}
