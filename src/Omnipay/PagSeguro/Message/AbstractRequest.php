<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PagSeguro\Message;

use Omnipay\Common\Message\AbstractRequest as BaseRequest;
use Guzzle\Http\Client;
use Guzzle\Common\Event;

use Omnipay\PagSeguro\ValueObject\Payment\PaymentRequest;
use Omnipay\PagSeguro\ValueObject\Credentials;
use Omnipay\PagSeguro\ValueObject\Item;
use Omnipay\PagSeguro\PaymentService;

/**
 * PagSeguro Abstract Request
 */
abstract class AbstractRequest extends BaseRequest
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://ws.pagseguro.uol.com.br/v2/checkout';

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getCurrency()
    {
        return 'BRL';
    }

    public function getCharset()
    {
        return 'UTF-8';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getData()
    {
        $data = array();

        $data['credentials'] = new Credentials(
            $this->getEmail(),
            $this->getToken()
        );

        $data['paymentRequest'] = new PaymentRequest(
            array( // Coleção de itens a serem pagos (O limite de itens é definido pelo webservice da Pagseguro)
                new Item(
                    '1', // ID do item
                    'Televisão LED 500 polegadas', // Descrição do item
                    8999.99 // Valor do item
                ),
                new Item(
                    '2', // ID do item
                    'Video-game mega ultra blaster', // Descrição do item
                    799.99 // Valor do item
                )
            )
        );

        return $data;
    }

    public function send()
    {
        $data = $this->getData();

        $service = new PaymentService($data['credentials']); // cria instância do serviço de pagamentos

        try {
            $httpResponse = $service->send($data['paymentRequest']);

            echo $httpResponse->getRedirectionUrl(); exit;

            header('Locaton: ' . $httpResponse->getRedirectionUrl()); // Redireciona o usuário
            exit;
        } catch (Exception $error) { // Caso ocorreu algum erro
            echo $error->getMessage(); // Exibe na tela a mensagem de erro
        }

        // Isto não deve funcionar
        //return $this->response = new Response($this, $httpResponse->getBody());

    }

}
