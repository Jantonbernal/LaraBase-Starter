<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\VerifyCode;
use App\Http\Requests\VerifyEmail;
use App\Http\Resources\UserResource;
use App\Mail\ForgotPassword;
use App\Models\Company;
use App\Models\User;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    use Loggable;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
            'status'    => '1'
        ]);

        $user = User::with('photo')->where('email', $credentials['email'])->first();

        // Verificar que no sea un usuario eliminado lógicamente y que la contraseña sea correcta
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Si el usuario está desactivado retornar error 403
        if ($user->status !== Status::ACTIVE) {
            return response()->json(['message' => 'Cuenta desactivada'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    public function logout(Request $request)
    {
        // Eliminar el token de acceso actual del usuario autenticado
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function forgotPassword(VerifyEmail $request)
    {
        DB::beginTransaction();

        try {
            $company = Company::with('logo')->first();

            // Verifica que el usuario exista y este activo el usuario
            $user = User::where([
                ['email', $request->email],
                ['status', Status::ACTIVE],
            ])->first();

            // Si no existe retorna error 500
            if (!$user) {
                return response()->json(['message' => "Lo siento, su correo electronico es inválido."], 500);
            }

            // Genera un valor random de 4 digitos
            $random = rand(1111, 9999);
            // Y lo guarda en el usuario
            $user->verification_code = $random;
            $user->save();

            // Datos para el correo electronico de recuperación de contraseña
            $data = [
                'hideEmail' => $request->hideEmail ?? $user->email,
                'name' => $user->name,
                'email' => $user->email,
                'code' => $random,
            ];

            Mail::to($user->email)->queue(new ForgotPassword($data, $company));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hemos enviado un código de verificación al correo electrónico ' . $request->hideEmail,
            ], 201);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Error al enviar E-mail');
        }
    }

    public function verifyCode(VerifyCode $request)
    {
        try {
            // Verificar código de seguridad
            $user = User::where([
                ['email', $request->email],
                ['verification_code', $request->code],
                ['status', Status::ACTIVE],
            ])->first();

            if (!$user) {
                return response()->json(['message' => "Correo Electrónico / Código de seguridad inválido"], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Verificación exitosa! ',
            ], 201);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Error al verificar el código');
        }
    }

    public function resetPassword(ResetPassword $request)
    {
        DB::beginTransaction();

        try {
            // Verificar que exista el E-mail y el código de seguridad
            $user = User::where([
                ['email', $request->email],
                ['verification_code', $request->code],
                ['status', Status::ACTIVE],
            ])->first();

            if (!$user) {
                return response()->json(['message' => "Correo Electrónico / Código de seguridad inválido"], 500);
            }

            // Actualizó la nueva contraseña y seteo a vacío el código de verificación
            $user->password = Hash::make($request->password);
            $user->verification_code = null;

            // Guarda el usuario con la nueva contraseña y retorna éxito al cliente si todo sale bien
            if ($user->save()) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Contraseña restablecida correctamente. Ahora puede iniciar sesión con su nueva contraseña.',
                ], 201);
            }

            return response()->json(['message' => "Ha ocurrido un error"], 500);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Error al restablecer contraseña');
        }
    }
}
