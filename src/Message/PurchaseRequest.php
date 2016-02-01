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

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'purse',
            'amount', 'currency', 'description',
            'returnUrl', 'cancelUrl', 'notifyUrl'
        );

        return [
            'ECM_PURCH_DESC'  => $this->getDescription(),
            'ECM_INV_NO'      => $this->getTransactionId(),
            'ECM_PAYEE_ID'    => $this->getPurse(),
            'ECM_ITEM_COST'   => $this->getAmount(),
            'ECM_QTY'         => '1',
            'ECM_RESULT_URL'  => $this->getNotifyUrl(),
            'ECM_SUCCESS_URL' => $this->getReturnUrl(),
            'ECM_FAIL_URL'    => $this->getCancelUrl(),
        ];
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
