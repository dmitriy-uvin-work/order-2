<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {
        $data = $request->except(['_token']);

        if (!$user = $this->getUserWiaEmail($data['email'])) {
            return redirect()->back()->withErrors(['Введен неверный логин или пароль']);
        }

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->back()->withErrors(['Введен неверный логин или пароль']);
        }
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.unique' => 'Пользователь с таким email уже зарегистрирован',
            'password.min' => 'Пароль должен состоять минимум из 6 символов',
            'password.confirmed' => 'Неправильно указан подтверждающий пароль',
        ]);

        $data = $request->all();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('admin.home')->with('success','Добро пожаловать!');
    }

    public function showForgotPassword()
    {
        return view('admin.auth.forgot');
    }

    public function postForgotPassword(Request $request)
    {
        $data = $request->except(['_token']);

        if (!$user = $this->getUserWiaEmail($data['email'])) {
            return redirect()->back()->withErrors(['Пользователь не найден']);
        }

        // Создать токен для сброса пароля
        $this->insertToken($data['email']);

        // Получить токен, только что созданный
        $tokenData = $this->getTokenViaEmail($data['email']);

        // Send message to email
        try {
            $this->sendResetLinkToEmail($user, $tokenData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Ссылка сброса была отправлена на ваш адрес электронной почты');
    }

    public function showResetPassword(Request $request)
    {
        $email = $request->input('email');
        return view('admin.auth.reset', compact('email'));
    }

    public function postResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'password.min' => 'Пароль должен состоять минимум из 6 символов',
            'password.confirmed' => 'Неправильно указан подтверждающий пароль',
        ]);

        $password = $request->password;

        // Проверить токен
        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$tokenData) return redirect()->route('login')->withErrors(['Недействительная ссылка']);

        $user = User::whereEmail($tokenData->email)->first();

        // Перенаправить пользователя обратно, если адрес электронной почты недействителен
        if (!$user) return redirect()->back()->withErrors(['Email not found']);

        $user->password =  bcrypt($password);
        $user->update();

        // Удалить токен
        DB::table('password_resets')->whereEmail($user->email)->delete();

        // Send message to email
        try {
            $this->SendSuccessNotificationToMail($tokenData->email);
        } catch (\Exception $e) {

        }

        return redirect()->route('admin.login.show')->with('success', 'Ваш пароль успешно изменён');

    }

    private function getUserWiaEmail($email) {
        return User::whereEmail($email)->first();
    }

    private function insertToken($email) {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);
    }

    private function getTokenViaEmail($email) {
        return DB::table('password_resets')->whereEmail($email)->first();
    }

    private function sendResetLinkToEmail($user, $tokenData)
    {
        $email = $user->email;
        $token = $tokenData;
        $encodedEmail = urlencode($email);
        $link = URL::to('/admin/reset-password/' . $token . '?email=' . $encodedEmail);

        Mail::send('admin.mail.reset-password', ['email'=>$email, 'link'=>$link], function ($message) use ($email) {
            $message->from('info@avocado.uz');
            $message->to($email);
            $message->subject('Восстановление пароля');
        });
    }

    private function SendSuccessNotificationToMail($email)
    {
        Mail::send('admin.mail.reset-password-success', ['email'=>$email], function ($message) use ($email) {
            $message->from('info@avocado.uz');
            $message->to($email);
            $message->subject('Ваш пароль успешно изменён');
        });
    }
}
