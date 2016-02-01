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

class PurchaseRequestTest extends TestCase
{
    private $request;

    private $purse          = 'vip.vip@corporation.inc';
    private $secret         = '22SAD#-78G888';
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

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->purse,         $data['ECM_PAYEE_ID']);
        $this->assertSame($this->returnUrl,     $data['ECM_SUCCESS_URL']);
        $this->assertSame($this->cancelUrl,     $data['ECM_FAIL_URL']);
        $this->assertSame($this->notifyUrl,     $data['ECM_RESULT_URL']);
        $this->assertSame($this->description,   $data['ECM_PURCH_DESC']);
        $this->assertSame($this->transactionId, $data['ECM_INV_NO']);
        $this->assertSame($this->amount,        $data['ECM_ITEM_COST']);
        $this->assertSame($this->quantity,      $data['ECM_QTY']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\eCoin\Message\PurchaseResponse', get_class($response));
    }
}
