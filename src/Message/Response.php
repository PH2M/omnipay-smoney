<?php

namespace Omnipay\Smoney\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * Result codes, see http://api.s-money.fr/documentation/utiliser-l-api/paiement-par-carte-bancaire/?lang=en
     * 0 : No errors.
     * 2 : The merchant needs to contact the bank of the holder.
     * 5 : Payment refused.
     * 17 : Client cancellation.
     * 30 : Request format error. To compare with the value of vads_extra_result.
     * 96 : Technical error of the payment.
     */
    const RESULT_CODE_SUCCESS = 0;
    const RESULT_CODE_MERCHAND_SHOULD_CONTACT_BANK = 2;
    const RESULT_CODE_PAYMENT_REFUSED = 5;
    const RESULT_CODE_CLIENT_CANCELLATION = 17;
    const RESULT_CODE_REQUEST_FORMAT_ERROR = 30;
    const RESULT_CODE_TECHNICAL_ERROR = 96;
    
    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->getDecodedData()->ErrorCode ?? $this->getDecodedData()->Code;
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $code = $this->getCode();
        return !$code || $code && 200 === $code;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getDecodedData()->Href;
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return object
     */
    protected function getDecodedData()
    {
        return json_decode($this->data);
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->getDecodedData()->ErrorMessage ?? '';
    }
}
