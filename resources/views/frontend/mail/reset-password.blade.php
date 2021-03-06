<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        * {
            text-decoration: none!important;
        }
        p {
            font: 14px Arial, sans-serif;color: #000000;line-height: 22px;
        }
        a {
            color: #ee2e2e;
            display: inline-block;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>

@php
    $site = route('home');
@endphp
<table border="0" cellpadding="20" cellspacing="0" style="max-width: 600px;">
    <tbody>
    <tr>
        <td>
            <p>Здравствуйте, {{ $send['name'] }}</p>
            <p>Вы или кто-то другой указали данный адрес для восстановление доступа к сайту <a
                    href="{{ $site }}">{{ $site }}</a> <br>
                Если вы не регистрировались на этом сайте, просто проигнорируйте это письмо и удалите его.</p>
            <br>
            --------------------------------
            <br>

            <p>Ваш логин на сайте: {{ $send['email'] }}</p>

            <p>Задать новый пароль вы можете по следующей ссылке:</p>

            <p><a href="{{ $send['link']}}">{{ $send['link'] }}</a></p>

            --------------------------------
        </td>
    </tr>
    </tbody>
</table>
<table border="0" cellpadding="20" cellspacing="0" style="max-width: 600px;">
    <tbody>
    <tr>
        <td>
            <p>С уважением,</p>
            <p>Администрация <a href="{{ $site }}">{{ $site }}</a></p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>













