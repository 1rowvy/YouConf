@extends('emails.layout')

@section('content')

    <h1 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 700; color: #1a1a1a; line-height: 1.3;">
        Статус работы изменён
    </h1>
    <p style="margin: 0 0 28px 0; font-size: 15px; color: #6b7280; line-height: 1.7;">
        Здравствуйте, <strong style="color: #1a1a1a;">{{ $firstName }}</strong>!
        Эксперт проверил вашу работу и изменил её статус.
    </p>

    <div style="background-color: #F9F9FB; border-radius: 8px; padding: 16px 20px;
                margin-bottom: 24px; border-left: 3px solid #1a1a1a;">
        <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 600; color: #9ca3af;
                  text-transform: uppercase; letter-spacing: 0.06em;">
            Работа
        </p>
        <p style="margin: 0; font-size: 15px; font-weight: 600; color: #1a1a1a; line-height: 1.5;">
            {{ $thesisTitle }}
        </p>
    </div>

    <table cellpadding="0" cellspacing="0" border="0"
           style="width: 100%; margin-bottom: 28px;">
        <tr>
            <td style="width: 44%; text-align: center; padding: 14px 16px;
                       background-color: #fef2f2; border-radius: 8px;">
                <p style="margin: 0 0 3px 0; font-size: 10px; font-weight: 700; color: #ef4444;
                          text-transform: uppercase; letter-spacing: 0.07em;">
                    Было
                </p>
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #b91c1c;
                          line-height: 1.4;">
                    {{ $oldStatus }}
                </p>
            </td>
            <td style="width: 12%; text-align: center; vertical-align: middle;
                       font-size: 22px; color: #d1d5db; padding: 0 4px;">
                →
            </td>
            <td style="width: 44%; text-align: center; padding: 14px 16px;
                       background-color: #f0fdf4; border-radius: 8px;">
                <p style="margin: 0 0 3px 0; font-size: 10px; font-weight: 700; color: #16a34a;
                          text-transform: uppercase; letter-spacing: 0.07em;">
                    Стало
                </p>
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #15803d;
                          line-height: 1.4;">
                    {{ $newStatus }}
                </p>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="background-color: #1a1a1a; border-radius: 9999px;">
                <a href="{{ $url }}"
                   style="display: inline-block; padding: 14px 36px; font-size: 14px;
                          font-weight: 600; color: #ffffff; text-decoration: none;
                          border-radius: 9999px; line-height: 1; letter-spacing: 0.01em;">
                    Посмотреть тезис
                </a>
            </td>
        </tr>
    </table>

@endsection
