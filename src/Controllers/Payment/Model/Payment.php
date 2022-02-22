<?php

declare(strict_types=1);

namespace App\Controllers\Payment\Model;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class Payment
{

    private int $loanTransactionId;
    private int $totalPay;

    public function getLoanTransactionId(): int
    {
        return $this->loanTransactionId;
    }

    public function setLoanTransactionId($loanTransactionId): void
    {
        try {
            $this->loanTransactionId = $loanTransactionId;
        } catch (\Throwable $e) {
            $this->loanTransactionId = 0;
        }
    }

    public function getTotalPay(): int
    {
        return $this->totalPay;
    }

    public function setTotalPay($totalPay): void
    {
        try {
            $this->totalPay = $totalPay;
        } catch (\Throwable $e) {
            $this->totalPay = 0;
        }
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request)
    {
        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Validation\\Rules')
                ->withExceptionNamespace('App\\Validation\\Exceptions')
        );

        $this->setLoanTransactionId($request['loanTransactionId']);
        $this->setTotalPay($request['totalPay']);

        $paymentValidator = v::attribute('loanTransactionId', v::LoanTransactionRule())
            ->attribute('totalPay', v::number()->between(1000, 10000));

        $errorMessage = [];
        try {
            $paymentValidator->assert($this);
        } catch (NestedValidationException $ex) {
            $messages = $ex->getMessages();
            foreach ($messages as $key => $message) {
                $errorMessage[] = [
                    "status" => $key,
                    "message" => "Failed Validation",
                    "errors" => $message
                ];
            }
        }
        return $errorMessage;
    }

}