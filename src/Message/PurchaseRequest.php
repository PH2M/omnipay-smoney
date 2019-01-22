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
        $this->validate(
            'token',
            'amount',
            'transactionId',
            'cancelUrl',
            'returnUrl',
            'description',
            'customerName',
            'customerEmail',
            'appAccountId'
        );

        $data = [
            'accessToken'       => $this->getToken(),
            'amount'            => $this->getParameter('amount'),
            'orderId'           => $this->getTransactionId(),
            'availableCards'    => 'CB',
            'beneficiary'       => [
                'appaccountid'  => $this->getAppAccountId()
            ],
            'message'           => $this->getDescription(),
            'ismine'            => false,
            'Require3DS'        => true,
            'urlReturn'         => $this->getCancelUrl(),
            'urlCallback'       => $this->getReturnUrl(),
            'fee'               => 0,
            'payerInfo'         => [
                'Name'          => $this->getCustomerName(),
                'Mail'          => $this->getCustomerEmail()
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

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->getParameter('customerName');
    }

    /**
     * @param $customerName
     * @return PurchaseRequest
     */
    public function setCustomerName($customerName)
    {
        return $this->setParameter('customerName', $customerName);
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getParameter('customerEmail');
    }

    /**
     * @param $customerEmail
     * @return PurchaseRequest
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setParameter('customerEmail', $customerEmail);
    }

    /**
     * @return string
     */
    public function getAppAccountId()
    {
        return $this->getParameter('appAccountId');
    }

    /**
     * @param $appAccountId
     * @return PurchaseRequest
     */
    public function setAppAccountId($appAccountId)
    {
        return $this->setParameter('appAccountId', $appAccountId);
    }
}
