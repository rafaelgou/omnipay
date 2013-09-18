<?php
namespace Omnipay\PagSeguro;

use Omnipay\PagSeguro\Codec\TransactionDecoder;
use Omnipay\PagSeguro\ValueObject\Credentials;
use Omnipay\PagSeguro\Http\Client;

class NotificationService
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/transactions/notifications';

    /**
     * @var Omnipay\PagSeguro\ValueObject\Credentials
     */
    private $credentials;

    /**
     * @var Omnipay\PagSeguro\Http\Client
     */
    private $client;

    /**
     * @var Omnipay\PagSeguro\Codec\TransactionDecoder
     */
    private $decoder;

    /**
     * @param Omnipay\PagSeguro\ValueObject\Credentials $credentials
     * @param Omnipay\PagSeguro\Http\Client $client
     * @param Omnipay\PagSeguro\Codec\TransactionDecoder $decoder
     */
    public function __construct(
        Credentials $credentials,
        Client $client = null,
        TransactionDecoder $decoder = null
    ) {
        $this->credentials = $credentials;
        $this->client = $client ?: new Client();
        $this->decoder = $decoder ?: new TransactionDecoder();
    }

    /**
     * @param string $code
     * @return Omnipay\PagSeguro\ValueObject\Transaction
     */
    public function getByCode($code)
    {
        $content = $this->client->get(
            static::ENDPOINT . '/' . $code
            . '?email=' . $this->credentials->getEmail()
            . '&token=' . $this->credentials->getToken()
        );

        return $this->decoder->decode($content);
    }
}
