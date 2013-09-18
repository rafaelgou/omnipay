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

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\PagSeguro\ValueObject\Payment\PaymentResponse;


class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, PaymentResponse $data)
    {
        $this->request = $request;

        if (empty($data)) {
            throw new InvalidResponseException;
        }

        $this->data = $data;
    }

    public function isSuccessful()
    {
        return ;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data->getRedirectionUrl();
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }
    
    public function getTransactionReference()
    {
        return false;
    }

    public function getMessage()
    {
        return false;
    }
}
