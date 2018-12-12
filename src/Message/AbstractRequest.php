<?php

namespace Omnipay\Smoney\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @var string
     */
    protected $endpoint = 'https://rest-pp.s-money.fr/api/sandbox';

    /**
     * @return string
     */
    abstract public function getEndpoint();

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @param array $data
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function sendData($data)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/vnd.s-money.v2+json'
        ];
        $body = $data ? json_encode($data) : null;
        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);

        return new Response($this, $httpResponse->getBody()->getContents());
    }
}
