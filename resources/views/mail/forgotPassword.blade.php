<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de seguridad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
        }

        .logo {
            max-height: 60px;
            margin-bottom: 7px;
        }

        h2 {
            color: #333333;
        }

        p {
            color: #666666;
            font-size: 16px;
            line-height: 1.5;
        }

        .code {
            font-size: 24px;
            color: #1a73e8;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .footer {
            font-size: 13px;
            color: #999999;
            text-align: center;
            margin-top: 30px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if (!empty($company->logo?->path))
                <img src="{{ asset('storage/' . $company->logo->path) }}" alt="{{ $company->name }}" class="logo">
            @endif
            <h2>{{ $company->name }}</h2>
        </div>

        <p>Hola {{ $data['name'] }},</p>

        <p>
            Has solicitado un código de recuperación para acceder a tu cuenta que termina en {{ $data['hideEmail'] }} .
            Por favor, utiliza el siguiente código
            para completar tu proceso:
        </p>

        <div class="code">{{ $data['code'] }}</div>

        <p>Este código es válido por un tiempo limitado. Si no solicitaste este código, puedes ignorar este correo.</p>

        <div class="footer">
            &copy; {{ now()->year }} {{ $company->name }}. Todos los derechos reservados.
        </div>
    </div>
</body>

</html>
