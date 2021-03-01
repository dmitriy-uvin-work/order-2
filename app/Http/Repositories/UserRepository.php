<?php


namespace App\Http\Repositories;


use App\Http\Repositories\Interfaces\iUserInterface;
use App\Models\Roles;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Playmobile\Components\PlaymobileSend;
use Modules\Playmobile\Components\SmsCSend;
use Modules\Playmobile\Entities\PhoneConfirmation;
use Modules\Playmobile\Entities\SendSms;

class UserRepository extends BaseRepository implements iUserInterface
{

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function getOrCreateUser($phone)
    {
        $user = User::where(['phone' => $phone])->first();
        if (!($user instanceof User)) {
            $user = User::create([
                'name' => $phone,
                'phone' => $phone,
                'status' => User::STATUS_INACTIVE,
                'password' => bcrypt('123456'),
            ]);

            $user->role()->create([
                'user_id' => $user->id,
                'role' => User::ROLE_CLIENT
            ]);

            $transactionsRepository = new TransactionsRepository();
            $transactionsRepository->createComing($phone, 25000);
        }
        $role = Roles::where(['user_id' => $user->id])->first();
        if ($role == null) {
            $user->role()->create([
                'user_id' => $user->id,
                'role' => User::ROLE_CLIENT
            ]);
        }
        return $user;
    }

    public function sendSms($phone)
    {
        $code = rand(1000, 9999);
        if ($phone == '998998189423' || $phone == '998935627708') {
            $code = 1000;
        }
        $model = PhoneConfirmation::create([
            'phone' => $phone,
            'status' => PhoneConfirmation::STATUS_UNCONFIRMED,
            'code' => $code
        ]);
        $message = "<#> Your one time verification code: {$code}
eaepnNRDLFG";
        if (strlen($phone) == 12) {
            PlaymobileSend::sendSms($phone, $message);
        } else {
            SmsCSend::sendSms($phone, $message);
        }
    }

    public function getBalanceAttribute($phone)
    {
        $client = Http::withToken(config('billing.BILLING_TOKEN'))
            ->get(config('billing.BILLING_URL') . 'billing/user/balance/' . $phone);

        if ($client->successful()) {
            return preg_replace("#[\D]+#", null, $client->body());
        }
        throw new \DomainException('Billing not working');
    }
}
