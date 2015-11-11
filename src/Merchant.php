<?php

/*
 * eCoin driver for Omnipay PHP payment library
 *
 * @link      https://github.com/hiqdev/omnipay-ecoin
 * @package   omnipay-ecoin
 * @license   MIT
 * @copyright Copyright (c) 2015, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\php\merchant\ecoin;

class Merchant extends \hiqdev\php\merchant\Merchant
{
    protected static $_defaults = [
        'system'      => 'ecoin',
        'label'       => 'eCoin',
        'actionUrl'   => 'https://ecoin.cc/account/mpay/',
        'confirmText' => 'OK',
    ];

    public function getInputs()
    {
        return [
            'ECM_PURCH_DESC'  => $this->description,
            'ECM_INV_TITLE'   => $this->invTitle,
            'ECM_PAYEE_ID'    => $this->purse,
            'ECM_ITEM_COST'   => $this->total,
            'ECM_QTY'         => $this->quantity,
            'ECM_RESULT_URL'  => $this->confirmUrl,
            'ECM_SUCCESS_URL' => $this->successUrl,
            'ECM_FAIL_URL'    => $this->failureUrl,
        ];
    }

    public function getInvTitle()
    {
        return $this->username;
    }

    public function validateConfirmation($data)
    {
        $str = $data['ECM_TRANS_ID'] . $data['ECM_TRANS_DATE'] . $this->purse . $data['ECM_PAYER_ID'] . $data['ECM_ITEM_COST'] . $data['ECM_QTY'] . $this->_secret;
        if (md5($str) !== strtolower($data['ECM_HASH'])) {
            return 'wrong hash';
        }
        $this->mset([
            'from' => $data['ECM_PAYER_ID'],
            'txn'  => $data['ECM_TRANS_ID'],
            'sum'  => $data['ECM_ITEM_COST'],
            'time' => date('c', strtotime($data['ECM_TRANS_DATE'])),
        ]);

        return;
    }
}
