<?php
namespace Omnipay\PagSeguro;

use Omnipay\PagSeguro\ValueObject\Payment\PaymentResponse;
use Omnipay\PagSeguro\ValueObject\Payment\PaymentRequest;
use Omnipay\PagSeguro\ValueObject\Credentials;
use Omnipay\PagSeguro\Codec\PaymentEncoder;
use Omnipay\PagSeguro\Codec\PaymentDecoder;
use Omnipay\PagSeguro\Http\Client;

class PaymentService
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/checkout';

    /**
     * @var Omnipay\PagSeguro\ValueObject\Credentials
     */
    private $credentials;

    /**
     * @var Omnipay\PagSeguro\Http\Client
     */
    private $client;

    /**
     * @var Omnipay\PagSeguro\Codec\PaymentEncoder
     */
    private $encoder;

    /**
     * @var Omnipay\PagSeguro\Codec\PaymentDecoder
     */
    private $decoder;

    /**
     * @param Omnipay\PagSeguro\ValueObject\Credentials $credentials
     * @param Omnipay\PagSeguro\Http\Client $client
     * @param Omnipay\PagSeguro\Codec\PaymentEncoder $encoder
     * @param Omnipay\PagSeguro\Codec\PaymentDecoder $decoder
     */
    public function __construct(
        Credentials $credentials,
        Client $client = null,
        PaymentEncoder $encoder = null,
        PaymentDecoder $decoder = null
    ) {
        $this->credentials = $credentials;
        $this->client = $client ?: new Client();
        $this->encoder = $encoder ?: new PaymentEncoder();
        $this->decoder = $decoder ?: new PaymentDecoder();
    }

    /**
     * @param Omnipay\PagSeguro\ValueObject\Payment\PaymentRequest $request
     * @return Omnipay\PagSeguro\ValueObject\Payment\PaymentResponse
     */
    public function send(PaymentRequest $request)
    {
        $content = $this->client->post(
            static::ENDPOINT,
            $this->encoder->encode($this->credentials, $request)
        );

        return $this->decoder->decode($content);
    }
}
