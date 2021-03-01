<?php


namespace App\Http\Repositories;


use App\Http\Repositories\Interfaces\iTransactionsInterface;
use Illuminate\Support\Facades\Http;

class TransactionsRepository extends BaseRepository implements iTransactionsInterface
{
    /**
     * @param $phone
     * @return mixed|void
     */
    public function getAllTransactionsByPhone($phone)
    {
        // TODO: Implement getAllTransactionsByPhone() method.
    }

    public function getUserBalance($phone)
    {
        $client = Http::withToken(config('billing.BILLING_TOKEN'))
            ->get(config('billing.BILLING_URL') . 'billing/user/balance/' . $this->phone);

        return $client->body();
    }

    public function getTransactionsList($phone = null, $type = null, $page = null, $pageSize = null)
    {
        $client = Http::withToken(config('billing.BILLING_TOKEN'))
            ->get(config('billing.BILLING_URL') . 'billing/invoice/transaction?pageSize='.$pageSize.'&phone=' . $phone . '&type=' . $type);

        return $client->json();
    }

    public function createExpense($user_id, $order_id, $amount)
    {
        $client = Http::withToken(config('billing.BILLING_TOKEN'))
            ->post(config('billing.BILLING_URL') . 'billing/invoice/expense', [
                'user_id' => $user_id,
                'order_id' => $order_id,
                'amount' => $amount,
            ]);

        if ($client->successful()) {
            return $client->json();
        }
        throw new \DomainException('Billing not saved', 422);
    }

    public function createComing($phone, $amount)
    {
        $client = Http::withToken(config('billing.BILLING_TOKEN'))
            ->post(config('billing.BILLING_URL') . 'billing/invoice/create-coming', [
                'user_id' => $phone,
                'payment_data' => 'For registered',
                'amount' => $amount
            ]);

        if ($client->successful()) {
            return $client->json();
        }
        throw new \DomainException('Billing not saved', 422);
    }

}
