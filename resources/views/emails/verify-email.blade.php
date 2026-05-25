@extends('emails.layout')

@section('content')

    <h1 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 700; color: #1a1a1a; line-height: 1.3;">
        Добро пожаловать в YouConf!
    </h1>
    <p style="margin: 0 0 28px 0; font-size: 15px; color: #6b7280; line-height: 1.7;">
        Для завершения регистрации, пожалуйста, подтвердите ваш адрес электронной почты, нажав на кнопку ниже.
    </p>

    <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 28px;">
        <tr>
            <td style="background-color: #1a1a1a; border-radius: 9999px;">
                <a href="{{ $url }}"
                   style="display: inline-block; padding: 14px 36px; font-size: 14px;
                          font-weight: 600; color: #ffffff; text-decoration: none;
                          border-radius: 9999px; line-height: 1; letter-spacing: 0.01em;">
                    Подтвердить почту
                </a>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 20px 0; font-size: 13px; color: #9ca3af; line-height: 1.6;">
        Ссылка действительна в течение <strong style="color: #6b7280;">60 минут</strong>.
    </p>

    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 0 0 20px 0;">

    <p style="margin: 0 0 6px 0; font-size: 12px; color: #9ca3af; line-height: 1.5;">
        Если кнопка не работает, скопируйте ссылку в адресную строку браузера:
    </p>
    <p style="margin: 0; font-size: 12px; color: #6b7280; word-break: break-all; line-height: 1.5;">
        <a href="{{ $url }}" style="color: #6b7280; text-decoration: underline;">{{ $url }}</a>
    </p>

@endsection
