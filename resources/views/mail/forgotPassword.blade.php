<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml"
    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Código de seguridad</title>

    <style type="text/css">
        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6em;
            background-color: #f6f6f6;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            box-sizing: border-box;
            font-size: 14px;
            width: 100%;
            background-color: #f6f6f6;
            margin: 0;
        }

        @media only screen and (max-width: 640px) {
            body {
                padding: 0 !important;
            }

            h1 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h2 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h3 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h4 {
                font-weight: 800 !important;
                margin: 20px 0 5px !important;
            }

            h1 {
                font-size: 22px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 16px !important;
            }

            .container {
                padding: 0 !important;
                width: 100% !important;
            }

            .content {
                padding: 0 !important;
            }

            .content-wrap {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td></td>
            <td class="container" width="600" valign="top">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action"
                        bgcolor="#fff">
                        <tr>
                            <td class="content-wrap" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-block" valign="top">
                                            Usa el siguiente código de seguridad para la cuenta {{ $data['hideEmail'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block" valign="top">
                                            Código de seguridad: <strong>{{ $data['code'] }}</strong>
                                            <br>
                                            Si no solicitaste este código, puedes hacer caso omiso de este mensaje de
                                            correo electrónico. Otra persona puede haber escrito tu dirección de correo
                                            electrónico por error.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block" valign="top">
                                            Gracias, <br>
                                            &mdash; El equipo de cuentas de {{ $company->business_name }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
