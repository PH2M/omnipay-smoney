<?php

namespace Omnipay\Smoney\Message;

use Magento\Sales\Model\Order\Item;

/**
 * Class PurchaseRequest
 * @package Omnipay\Smoney\Message
 */
class PurchaseMultipleSellersRequest extends AbstractRequest
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

        $payments = $this->getPaymentsData();

        $data = [
            'accessToken'       => $this->getToken(),
            'orderId'           => $this->getTransactionId(),
            'availableCards'    => 'CB',
            'payments'          => $payments,
            'message'           => $this->getDescription(),
            'ismine'            => false,
            'Require3DS'        => true,
            'payerInfo'         => [
                'Name'          => $this->getCustomerName(),
                'Mail'          => $this->getCustomerEmail()
            ],
            'urlReturn'         => $this->getCancelUrl(),
            'urlCallback'       => $this->getReturnUrl()
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function getPaymentsData()
    {
        $amounts = unserialize($this->getParameter('amount'));
        $items = [];
        $i = 1;

        foreach ($amounts as $key => $amount) {
            $items[] = [
                'orderId'       => $this->getTransactionId() . '-' . $i,
                'beneficiary'   => ['appaccountid' => $key],
                'amount'        => $amount,
                'fee'           => 0,
            ];
            $i++;
        }

        return $items;
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
