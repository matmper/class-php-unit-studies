<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private $gateway;
    private $paymentTransactionRepository;
    private $paymentService;

    private $customerName;
    private $creditCardNumber;
    private $creditCardValidity;
    private $itemValue;

    private $customer;
    private $item;
    private $creditCard;

    public function setUp(): void
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);
        $this->paymentService = new PaymentService($this->gateway, $this->paymentTransactionRepository);

        $this->customer = $this->createMock(Customer::class);
        $this->creditCard = $this->createMock(CreditCard::class);
        $this->item = $this->createMock(Item::class);

        $this->customerName = fake()->name();
        $this->creditCardNumber = fake()->creditCardNumber('Visa');
        $this->creditCardValidity = fake()->dateTimeBetween('+1 year', '+10 years');
        $this->itemValue = fake()->randomFloat(2, 1);
    }

    /**
     * @test
     */
    public function shouldSaveWhenGatewayReturnOkWithRetries()
    {
        $this->customer->expects($this->exactly(3))->method('getName')->willReturn($this->customerName);
        $this->creditCard->expects($this->exactly(3))->method('getNumber')->willReturn($this->creditCardNumber);
        $this->creditCard->expects($this->exactly(3))->method('getValidity')->willReturn($this->creditCardValidity);
        $this->item->expects($this->exactly(4))->method('getValue')->willReturn($this->itemValue);
        
        $this->gateway
            ->expects($this->exactly(3))
            ->method('pay')
            ->with($this->customerName, $this->creditCardNumber, $this->creditCardValidity, $this->itemValue)
            ->willReturnOnConsecutiveCalls(false, false, true);

        $this->paymentTransactionRepository
            ->expects($this->once())
            ->method('save');

        $paymentTransaction = $this->paymentService->pay($this->customer, $this->item, $this->creditCard);

        $this->assertInstanceOf(PaymentTransaction::class, $paymentTransaction);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGatewayFails()
    {
        $this->customer->expects($this->exactly(3))->method('getName')->willReturn($this->customerName);
        $this->creditCard->expects($this->exactly(3))->method('getNumber')->willReturn($this->creditCardNumber);
        $this->creditCard->expects($this->exactly(3))->method('getValidity')->willReturn($this->creditCardValidity);
        $this->item->expects($this->exactly(3))->method('getValue')->willReturn($this->itemValue);

        $this->gateway
            ->expects($this->exactly(3))
            ->method('pay')
            ->with($this->customerName, $this->creditCardNumber, $this->creditCardValidity, $this->itemValue)
            ->willReturnOnConsecutiveCalls(false, false, false);

        $this->paymentTransactionRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $this->paymentService->pay($this->customer, $this->item, $this->creditCard);
    }
}