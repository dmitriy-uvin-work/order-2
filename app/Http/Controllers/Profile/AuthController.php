<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRegisterRequest;
use App\Http\Services\PlayMobileService;
use App\Models\Role;
use App\Models\UserVerification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    protected $playMobileService;

    public function __construct(PlayMobileService $playMobileService)
    {
        $this->playMobileService = $playMobileService;
    }

    /* --------------------- Register Actions --------------------- */

    public function getRegister()
    {
        return view('frontend.auth.register');
    }

    public function postRegister(ProfileRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $verification = UserVerification::whereNotNull('verified_at')->where([
                'value' => $request->value,
                'token' => $request->token
            ])->first();

            if ($verification) {
                if ($verification->type == $verification::TYPE_PHONE) {
                    $user = User::create([
                        'name' => $request->get('name'),
                        'surname' => $request->get('surname'),
                        'phone' => $request->get('value'),
                        'phone_verified_at' => Carbon::now(),
                        'password' => bcrypt($request->get('password')),
                    ]);
                }
                if ($verification->type == $verification::TYPE_EMAIL) {
                    $user = User::create([
                        'name' => $request->get('name'),
                        'surname' => $request->get('surname'),
                        'email' => $request->get('value'),
                        'email_verified_at' => Carbon::now(),
                        'password' => bcrypt($request->get('password')),
                    ]);
                }

                if (!isset($user)) {
                    throw new \DomainException('Время активация истекло, попробуйте заного', 500);
                } else {
                    Role::create([
                        'user_id' => $user->id,
                        'role' => $user::ROLE_CLIENT
                    ]);
                }
            } else {
                throw new \DomainException('Время активация истекло, попробуйте заного', 500);
            }

            UserVerification::where('value', $request->get('value'))->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }

        Auth::login($user);

        return redirect()->route('profile.index')->with('success', 'Вы успешно зарегистрировались!');
    }

    public function sendActivateCode(Request $request)
    {
        $email = $request->get('email');
        $phone = $request->get('phone');

        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                if (!empty($email)) {
                    $request->validate([
                        'email' => 'required|email|unique:users,email',
                    ]);

                    $verification = $this->sendActivateCodeEmail($email);

                } else if (!empty($phone)) {
                    $request->validate([
                        'phone' => 'required|numeric|unique:users,phone',
                    ]);

                    $verification = $this->sendActivateCodePhone($phone);

                } else {
                    throw new \DomainException('Некорректные входные данные');
                }

                $view = view('frontend.auth.components.confirm-activate-code', ['token' => $verification->token, 'value' => $verification->value])->render();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }

            return response()->json(['success' => true, 'view' => $view]);
        }

        throw new \DomainException('Method Not Found');
    }

    public function postConfirmActivateCode(Request $request)
    {
        if ($request->ajax()) {
            $token = $request->get('token');
            $code = $request->get('code');

            DB::beginTransaction();

            try {
                $verification = UserVerification::where(['token' => $token, 'code' => $code])->first();
                if ($verification) {
                    UserVerification::where('value', $verification->value)->where('id', '<>', $verification->id)->delete();
                    $verification->verified_at = now();
                    $verification->save();
                } else {
                    throw new \DomainException("Неверный код активации", 500);
                }

                DB::commit();

                $view = view('frontend.auth.components.register-complete', ['token' => $verification->token, 'value' => $verification->value])->render();

                return response()->json(['success' => true, 'view' => $view]);

            } catch (\Exception $exception) {
                DB::rollBack();
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }
        }

        throw new \DomainException("Method Not Found");
    }

    /* --------------------- Register Complete Actions --------------------- */

    public function sendActivateCodeEmail($email)
    {
        $verification = UserVerification::create([
            'code' => $this->playMobileService->generateCode(),
            'value' => $email,
            'type' => UserVerification::TYPE_EMAIL,
            'token' => Str::random(60),
        ]);

        $send['email'] = $email;
        $send['code'] = $verification->code;

        Mail::send('frontend.mail.activate-code-email', ['send' => $send], function ($message) use ($send) {
            $message->to($send['email']);
            $message->subject('Activate Code');
        });

        return $verification;
    }

    public function sendActivateCodePhone($phone)
    {
        $verification = UserVerification::create([
            'code' => $this->playMobileService->generateCode(),
            'value' => $phone,
            'type' => UserVerification::TYPE_PHONE,
            'token' => Str::random(60),
        ]);

        $this->playMobileService->sendCode($phone, $verification->code);

        return $verification;
    }


    /* --------------------- Login Actions --------------------- */

    public function getLogin()
    {
        return view('frontend.auth.login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $data = $request->except(['_token']);

        if (is_numeric($data['login'])) {
            if (Auth::attempt(['phone' => $data['login'], 'password' => $data['password']])) {
                return redirect()->route('profile.index');
            } else {
                return redirect()->back()->withErrors(['Введен неверный логин или пароль']);
            }
        } else {
            if (Auth::attempt(['email' => $data['login'], 'password' => $data['password']])) {
                return redirect()->route('profile.index');
            } else {
                return redirect()->back()->withErrors(['Введен неверный логин или пароль']);
            }
        }
    }

    /* --------------------- Restore Actions --------------------- */
    public function getRestore()
    {
        return view('frontend.auth.restore');
    }

    public function postRestore(Request $request)
    {
        $data = $request->except(['_token']);

        if (is_numeric($request->get('login'))) {
            $request->validate([
                'login' => 'required|numeric|exists:users,phone'
            ]);

            DB::beginTransaction();
            try {
                $code = $this->playMobileService->generateCode();
                $phone = $data['login'];

                DB::table('password_resets')->insert([
                    'phone' => $phone,
                    'code' => $code,
                    'created_at' => Carbon::now()
                ]);

                $tokenData = DB::table('password_resets')->wherePhone($phone)->orderBy('created_at', 'desc')->first();
                $this->playMobileService->sendCode($phone, $tokenData->code);
                DB::commit();
                return redirect()->route('profile.reset-password-by-phone', ['phone' => $phone])->with('success', 'На ваш телефон отправлен проверичный код');
            } catch (\Exception $exception) {
                DB::rollback();
                return redirect()->back()->with('error', $exception->getMessage());
            }
        } else {
            $request->validate([
                'login' => 'required|email|exists:users,email',
            ]);

            $user = User::whereEmail($data['login'])->select('name', 'email')->first();
            DB::beginTransaction();
            try {
                $email = $data['login'];
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => Str::random(60),
                    'created_at' => Carbon::now()
                ]);
                $tokenData = DB::table('password_resets')->whereEmail($email)->orderBy('created_at', 'desc')->first();
                $this->sendResetEmail($user, $tokenData->token);
                DB::commit();
                return redirect()->route('home')->with('success', 'Ссылка сброса была отправлена на ваш адрес электронной почты');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    private function sendResetEmail($user, $token)
    {
        // Генерируем ссылку для сброса пароля. Сгенерированный токен встроен в ссылку
        $encodedEmail = urlencode($user->email);
        $link = URL::to('/profile/reset-password/' . $token . '?email=' . $encodedEmail);

        $send['name'] = $user->name;
        $send['link'] = $link;
        $send['email'] = $user->email;

        Mail::send('frontend.mail.reset-password', ['send' => $send], function ($message) use ($send) {
            $message->to($send['email']);
            $message->subject('Сброса пароля');
        });
    }

    /* --------------------- Reset Actions --------------------- */

    public function getResetPassword(Request $request)
    {
        $email = $request->input('email');
        return view('frontend.auth.reset', compact('email'));
    }

    public function postResetPassword(Request $request)
    {
        $password = $request->password;

        // Проверить токен
        $tokenData = DB::table('password_resets')->where('token', $request->token)->orderBy('created_at', 'desc')->first();

        $sent_at = Carbon::createFromFormat('Y-m-d H:i:s', $tokenData->created_at);
        $diff_in_minutes = $sent_at->DiffInSeconds(Carbon::now());

        if ($diff_in_minutes > 900) return redirect()->route('home')->with('error', 'Недействительная ссылка');

        if (!$tokenData) return redirect()->route('home')->with('error', 'Недействительная ссылка');

        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::whereEmail($tokenData->email)->first();

        // Перенаправить пользователя обратно, если адрес электронной почты недействителен
        if (!$user) return redirect()->back()->with('error', 'Email not found');

        $user->password = bcrypt($password);
        $user->update();

        Auth::login($user);

        // Удалить токен
        DB::table('password_resets')->whereEmail($user->email)->delete();

        $this->sendSuccessEmail($user->name, $tokenData->email);

        return redirect()->route('home')->with('success', 'Ваш пароль успешно изменён');

    }

    private function sendSuccessEmail($name, $email)
    {
        try {

            $send = [
                'name' => $name,
                'email' => $email
            ];

            Mail::send('frontend.mail.reset-password-success', ['send' => $send], function ($message) use ($send) {
                $message->to($send['email']);
                $message->subject('Ваш пароль успешно изменён');
            });
        } catch (\Exception $exception) {

        }
    }

    // by phone
    public function getResetPasswordByPhone($phone)
    {
        return view('frontend.auth.reset-by-phone', compact('phone'));
    }

    public function postResetPasswordByPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'password' => 'required|confirmed|min:6',
        ], [
            'password.min' => 'Пароль должен состоять минимум из 6 символов',
            'password.confirmed' => 'Неправильно указан подтверждающий пароль',
        ]);

        $password = $request->password;

        // Проверить токен
        $tokenData = DB::table('password_resets')->where('code', $request->phone_confirm)->orderBy('created_at', 'desc')->first();

        if (!$tokenData) return redirect()->back()->with('error', 'Неправильный код проверки повторите попытку');

        $sent_at = Carbon::createFromFormat('Y-m-d H:i:s', $tokenData->created_at);
        $diff_in_minutes = $sent_at->DiffInSeconds(Carbon::now());

        if ($diff_in_minutes > 300) {
            return redirect()->route('home')->with('error', 'Недействительная проверичный код');
        }

        $user = User::wherePhone($tokenData->phone)->first();

        // Перенаправить пользователя обратно, если адрес электронной почты недействителен
        if (!$user) return redirect()->back()->with('error', 'Пользователь не найден');

        $user->password = bcrypt($password);
        $user->update();

        Auth::login($user);

        // Удалить токен
        DB::table('password_resets')->wherePhone($user->phone)->delete();

        return redirect()->route('home')->with('success', 'Ваш пароль успешно изменён');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
