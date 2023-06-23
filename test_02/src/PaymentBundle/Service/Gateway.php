<?php

namespace PaymentBundle\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;

class Gateway
{
    const BASE_URL = 'https://paymentgateway.ex';

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private $user,
        private $password
    ) {
        //
    }

    /**
     * Validate user and create a payment
     *
     * @param string $name
     * @param string $creditCardNumber
     * @param \DateTime|null $validity
     * @param float $value
     * @return boolean
     */
    public function pay(string $name, string $creditCardNumber, \DateTime $validity = null, float $value): bool
    {
        $token = $this->httpClient->send('POST', self::BASE_URL . '/authenticate', [
            'user' => $this->user,
            'password' => $this->password
        ]);

        if (empty($token)) {
            return $this->paymentFailed('Authentication failed');
        }

        $response = $this->httpClient->send('POST', self::BASE_URL . '/pay', [
            'name' => $name,
            'credit_card_number' => $creditCardNumber,
            'validity' => $validity,
            'value' => $value,
            'token' => $token
        ]);

        if ($response['paid'] !== true) {
            return $this->paymentFailed('Payment failed');
        }

        return true;
    }

    /**
     * Fail a payment request
     *
     * @param string $message
     * @return false
     */
    private function paymentFailed(string $message): false
    {
        $this->logger->log($message);
        return false;
    }
}