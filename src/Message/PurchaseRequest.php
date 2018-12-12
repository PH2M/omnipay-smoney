<?php

namespace Omnipay\Smoney\Message;

/**
 * Class PurchaseRequest
 * @package Omnipay\Smoney\Message
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('token', 'amount', 'transactionId', 'cancelUrl', 'returnUrl');

        $data = [
            'accessToken'       => $this->getToken(),
            'amount'            => $this->getParameter('amount'),
            'orderId'           => $this->getTransactionId(),
            'availableCards'    => 'CB',
            'beneficiary'       => [
                'appaccountid'  => 'creatik-com'
            ],
            'message'           => 'message de test',
            'ismine'            => false,
            'Require3DS'        => true,
            'urlReturn'         => $this->getCancelUrl(),
            'urlCallback'       => $this->getReturnUrl(),
            'fee'               => 0,
            'payerInfo'         => [
                'Name'          => 'Pierre Dupont',
                'Mail'          => 'pierre@dupont.fr'
            ],
            'extraparameters'   => [
                'systempaylanguage' => 'en'
            ]
        ];

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . '/payins/cardpayments';
    }
}
