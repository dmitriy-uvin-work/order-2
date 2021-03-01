<?php

namespace App\Http\Services\Paycom;

use App\Models\PaycomTransaction;

/**
 * Class Transaction
 *
 * Example MySQL table might look like to the following:
 *
 * CREATE TABLE `transactions` (
 *   `id` INT(11) NOT NULL AUTO_INCREMENT,
 *   `paycom_transaction_id` VARCHAR(25) NOT NULL COLLATE 'utf8_unicode_ci',
 *   `paycom_time` VARCHAR(13) NOT NULL COLLATE 'utf8_unicode_ci',
 *   `paycom_time_datetime` DATETIME NOT NULL,
 *   `create_time` DATETIME NOT NULL,
 *   `perform_time` DATETIME NULL DEFAULT NULL,
 *   `cancel_time` DATETIME NULL DEFAULT NULL,
 *   `amount` INT(11) NOT NULL,
 *   `state` TINYINT(2) NOT NULL,
 *   `reason` TINYINT(2) NULL DEFAULT NULL,
 *   `receivers` VARCHAR(500) NULL DEFAULT NULL COMMENT 'JSON array of receivers' COLLATE 'utf8_unicode_ci',
 *   `order_id` INT(11) NOT NULL,
 *
 *   PRIMARY KEY (`id`)
 * )
 *   COLLATE='utf8_unicode_ci'
 *   ENGINE=InnoDB
 *   AUTO_INCREMENT=1;
 *
 */
class Transaction
{
    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 43200000;

    const STATE_CREATED                  = 1;
    const STATE_COMPLETED                = 2;
    const STATE_CANCELLED                = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND         = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED            = 3;
    const REASON_CANCELLED_BY_TIMEOUT        = 4;
    const REASON_FUND_RETURNED               = 5;
    const REASON_UNKNOWN                     = 10;

    /** @var string Paycom transaction id. */
    public $paycom_transaction_id;

    /** @var int Paycom transaction time as is without change. */
    public $paycom_time;

    /** @var string Paycom transaction time as date and time string. */
    public $paycom_time_datetime;

    /** @var int Transaction id in the merchant's system. */
    public $id;

    /** @var string Transaction create date and time in the merchant's system. */
    public $create_time;

    /** @var string Transaction perform date and time in the merchant's system. */
    public $perform_time;

    /** @var string Transaction cancel date and time in the merchant's system. */
    public $cancel_time;

    /** @var int Transaction state. */
    public $state;

    /** @var int Transaction cancelling reason. */
    public $reason;

    /** @var int Amount value in coins, this is service or product price. */
    public $amount;

    /** @var string Pay receivers. Null - owner is the only receiver. */
    public $receivers;

    // additional fields:
    // - to identify order or product, for example, code of the order
    // - to identify client, for example, account id or phone number

    /** @var string Code to identify the order or service for pay. */
    public $order_id;

    /**
     * Saves current transaction instance in a data store.
     * @return bool true - on success
     */
    public function save()
    {
        // todo: Implement creating/updating transaction into data store
        // todo: Populate $id property with newly created transaction id

        if (!$this->id) {
            $transaction = new PaycomTransaction();
            $transaction->paycom_transaction_id = $this->paycom_transaction_id;
            $transaction->paycom_time = $this->paycom_time;
            $transaction->paycom_time_datetime = $this->paycom_time_datetime;
            $transaction->create_time = $this->create_time;
            $transaction->amount = 1 * $this->amount;
            $transaction->state = $this->state;
            $transaction->receivers = $this->receivers ? json_encode($this->receivers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
            $transaction->order_id = 1 * $this->order_id;
            $transaction->save();

            if ($transaction) {
                $this->id = $transaction->id;
            }
        } else {

            $perform_time = $this->perform_time ? $this->perform_time : null;
            $cancel_time  = $this->cancel_time ? $this->cancel_time : null;
            $reason       = $this->reason ? 1 * $this->reason : null;

            $transaction = PaycomTransaction::where(['id' => $this->id, 'paycom_transaction_id' => $this->paycom_transaction_id])->first();
            $transaction->perform_time = $perform_time;
            $transaction->cancel_time = $cancel_time;
            $transaction->state = 1 * $this->state;
            $transaction->reason = $reason;
            if ($this->amount) {
                $transaction->amount = 1 * $this->amount;
            }
            $transaction->save();
        }

        return $transaction;
    }

    /**
     * Cancels transaction with the specified reason.
     * @param int $reason cancelling reason.
     * @return void
     */
    public function cancel($reason)
    {
        // todo: Implement transaction cancelling on data store

        // todo: Populate $cancel_time with value
        $this->cancel_time = Format::timestamp2datetime(Format::timestamp());

        // todo: Change $state to cancelled (-1 or -2) according to the current state

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        // set reason
        $this->reason = $reason;

        // todo: Update transaction on data store
        $this->save();
    }

    /**
     * Determines whether current transaction is expired or not.
     * @return bool true - if current instance of the transaction is expired, false - otherwise.
     */
    public function isExpired()
    {
        // todo: Implement transaction expiration check
        // for example, if transaction is active and passed TIMEOUT milliseconds after its creation, then it is expired
        return $this->state == self::STATE_CREATED && abs(Format::datetime2timestamp($this->create_time) - Format::timestamp(true)) > self::TIMEOUT;
    }

    /**
     * Find transaction by given parameters.
     * @param mixed $params parameters
     * @return Transaction|Transaction[]
     * @throws PaycomException invalid parameters specified
     */
    public function find($params)
    {
        // todo: Implement searching transaction by id, populate current instance with data and return it
        if (isset($params['id'])) {
            $transaction = PaycomTransaction::where(['paycom_transaction_id' => $params['id']])->first();
        } elseif (isset($params['account'], $params['account']['order_id'])) {
            // todo: Implement searching transactions by given parameters and return the list of transactions
            // search by order id active or completed transaction
            $transaction = PaycomTransaction::where(['order_id' => $params['account']['order_id']])->whereIn('state', [1, 2])->first();
        } else {
            throw new PaycomException(
                $params['request_id'],
                'Parameter to find a transaction is not specified.',
                PaycomException::ERROR_INTERNAL_SYSTEM
            );
        }

        if ($transaction) {

            $this->id                    = $transaction->id;
            $this->paycom_transaction_id = $transaction->paycom_transaction_id;
            $this->paycom_time           = 1 * $transaction->paycom_time;
            $this->paycom_time_datetime  = $transaction->paycom_time_datetime;
            $this->create_time           = $transaction->create_time;
            $this->perform_time          = $transaction->perform_time;
            $this->cancel_time           = $transaction->cancel_time;
            $this->state                 = 1 * $transaction->state;
            $this->reason                = $transaction->reason ? 1 * $transaction->reason : null;
            $this->amount                = 1 * $transaction->amount;
            $this->receivers             = $transaction->receivers ? json_decode($transaction->receivers, true) : null;
            $this->order_id              = 1 * $transaction->order_id;

            return $this;
        }

        return null;
    }

    /**
     * Gets list of transactions for the given period including period boundaries.
     * @param int $from_date start of the period in timestamp.
     * @param int $to_date end of the period in timestamp.
     * @return array list of found transactions converted into report format for send as a response.
     */
    public function report(int $from_date, int $to_date)
    {
        $from_date = Format::timestamp2datetime($from_date);
        $to_date   = Format::timestamp2datetime($to_date);

        $transactions = PaycomTransaction::whereBetween('paycom_time_datetime', [$from_date, $to_date])->orderBy('paycom_time_datetime')->get();

        // assume, here we have $rows variable that is populated with transactions from data store
        // normalize data for response
        $result = [];
        foreach ($transactions as $transaction) {
            $result[] = [
                'id'           => $transaction->paycom_transaction_id, // paycom transaction id
                'time'         => 1 * $transaction->paycom_time, // paycom transaction timestamp as is
                'amount'       => 1 * $transaction->amount,
                'account'      => [
                    'order_id' => 1 * $transaction->order_id, // account parameters to identify client/order/service
                    // ... additional parameters may be listed here, which are belongs to the account
                ],
                'create_time'  => Format::datetime2timestamp($transaction->create_time),
                'perform_time' => Format::datetime2timestamp($transaction->perform_time),
                'cancel_time'  => Format::datetime2timestamp($transaction->cancel_time),
                'transaction'  => 1 * $transaction->id,
                'state'        => 1 * $transaction->state,
                'reason'       => 1 * $transaction->reason,
                'receivers'    => isset($transaction->receivers) ? json_decode($transaction->receivers, true) : null,
            ];
        }

        return $result;
    }
}
