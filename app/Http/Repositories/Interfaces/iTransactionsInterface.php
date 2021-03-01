<?php


namespace App\Http\Repositories\Interfaces;


interface iTransactionsInterface
{
    /**
     * @param $phone
     * @return mixed
     */
    public function getAllTransactionsByPhone($phone);

    /**
     * @param $phone
     * @return mixed
     */
    public function getUserBalance($phone);

    /**
     * @param null $phone
     * @param null $type
     * @param null $page
     * @param null $pageSize
     * @return mixed
     */
    public function getTransactionsList($phone = null, $type = null, $page = null, $pageSize = null);

    /**
     * @param $user_id
     * @param $order_id
     * @param $amount
     * @return mixed
     */
    public function createExpense($user_id, $order_id, $amount);
}
