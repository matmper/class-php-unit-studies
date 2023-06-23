<?php

namespace PaymentBundle\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;

class PaymentService
{
    public function __construct(
        private Gateway $gateway,
        private PaymentTransactionRepository $paymentTransactionRepository
    ) {
        //
    }

    /**
     * Create a payment transaction
     *
     * @param Customer $customer
     * @param Item $item
     * @param CreditCard $creditCard
     * @return PaymentTransaction
     */
    public function pay(Customer $customer, Item $item, CreditCard $creditCard): PaymentTransaction
    {
        for ($i = 0; $i < 3; $i++) {
            $paid = $this->gateway->pay(
                $customer->getName(),
                $creditCard->getNumber(),
                $creditCard->getValidity(),
                $item->getValue()
            );

            if ($paid === true) {
                break;
            }
        }

        if (empty($paid)) {
            throw new PaymentErrorException();
        }

        $paymentTransaction = new PaymentTransaction(
            $customer,
            $item,
            $item->getValue()
        );

        $this->paymentTransactionRepository->save($paymentTransaction);

        return $paymentTransaction;
    }
}
