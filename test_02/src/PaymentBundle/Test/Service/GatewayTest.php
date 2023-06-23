<?php

namespace Test\PaymentBundle\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotPayWhenAuthenticationFail()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, fake()->email(), fake()->password());

        $httpClient
            ->expects($this->once())
            ->method('send')
            ->willReturn('');

        $paid = $gateway->pay(
            fake()->name(),
            fake()->creditCardNumber('Visa'),
            new \DateTime('now'),
            fake()->randomFloat(2, 10, 100),
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function shouldNotPayWhenFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, fake()->email(), fake()->password());

        $httpClient
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturnOnConsecutiveCalls(fake()->md5(), ['paid' => false]);

        $logger
            ->expects($this->once())
            ->method('log')
            ->with('Payment failed');

        $name = fake()->name();
        $creditCardNumber = fake()->creditCardNumber('Visa');
        $value = fake()->randomFloat(2, 10, 100);
        $validity = new \DateTime('now');

        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, fake()->email(), fake()->password());

        $httpClient
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturnOnConsecutiveCalls(
                fake()->md5(), ['paid' => true]);

        $name = fake()->name();
        $creditCardNumber = fake()->creditCardNumber('Visa');
        $value = fake()->randomFloat(2, 10, 100);
        $validity = new \DateTime('now');
        
        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(true, $paid);
    }
}