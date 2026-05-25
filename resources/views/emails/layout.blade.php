<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouConf</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F9F9FB; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; color: #1a1a1a; -webkit-text-size-adjust: 100%;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background-color: #F9F9FB; padding: 40px 16px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                       style="max-width: 600px; width: 100%; background-color: #ffffff;
                              border-radius: 12px; overflow: hidden;
                              box-shadow: 0 1px 6px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background-color: #1a1a1a; padding: 20px 32px;">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="vertical-align: middle; padding-left: 14px;">
                                        <span style="display: block; font-size: 17px; font-weight: 700;
                                                     color: #ffffff; letter-spacing: -0.3px; line-height: 1.2;">
                                            YouConf
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 36px 32px 28px 32px;">
                            @yield('content')
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #F9F9FB; border-top: 1px solid #e5e7eb;
                                   padding: 20px 32px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.7;">
                                Это письмо отправлено автоматически системой YouConf. Если вы получили его по ошибке, просто проигнорируйте это сообщение.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
