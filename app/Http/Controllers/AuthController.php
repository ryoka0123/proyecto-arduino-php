<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('forms.inicioSesion');
    }

    public function showRegister()
    {
        return view('forms.registro');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (
            Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']]) ||
            Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']])
        ) {
            $request->session()->regenerate();
            return redirect()->route('microcontrolador');
        }

        return back()->with('error', 'Usuario o contraseña incorrectos.');
    }

    public function register(Request $request)
    {
        // 1. Validar que las contraseñas sean iguales
        if ($request->password1 !== $request->password2) {
            return back()->withInput()->withErrors(['password2' => 'Las contraseñas no coinciden.']);
        }

        // 2. Validar si el usuario o email ya existen
        $userExists = User::where('name', $request->username)->orWhere('email', $request->email)->exists();
        if ($userExists) {
            return back()->withInput()->withErrors(['username' => 'El usuario o email ya está registrado.']);
        }

        // 3. Validar el resto de campos
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password1' => 'required|string|min:6',
        ]);

        // 4. Crear el usuario
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password1),
        ]);

        Auth::login($user);

        return redirect()->route('microcontrolador');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('inicioSesion');
    }

    public function enviarOtp(Request $request)
    {
        // 1. Validar que el email exista en la base de datos
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'El correo no está registrado.');
        }

        // 2. Generar un código OTP de 6 dígitos
        $otp = rand(100000, 999999);

        // 3. Guardar el OTP en la sesión (puedes guardarlo en la base de datos si prefieres)
        session(['otp' => $otp, 'otp_email' => $user->email]);

        // 4. Enviar el OTP por correo (aquí solo ejemplo, debes configurar tu mail en .env)
        \Mail::raw("Tu código de recuperación es: $otp", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de recuperación de contraseña');
        });

        // 5. Redirigir a la vista de verificación de OTP
        return redirect()->route('vista_verificar_otp')->with('success', 'Se ha enviado un código a tu correo.');
    }

    public function vistaVerificarOtp(Request $request)
    {
        // Recupera el email de la sesión o del request (según tu flujo)
        $email = session('otp_email', $request->input('email'));

        return view('auth.verify_otp', ['email' => $email]);
    }

    public function verificarOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.digits' => 'Código no valido. Ingrese un código correcto.',
        ]);

        if (
            session('otp') == $request->otp &&
            session('otp_email') == $request->email
        ) {
            session(['reset_email' => $request->email]);
            return redirect()->route('vista_reset_password')->with('email', $request->email);
        } else {
            return back()->with('error', 'El código es incorrecto o ha expirado. Intenta nuevamente.');
        }
    }

    public function vistaResetPassword(Request $request)
    {
        // Recupera el email de la sesión (o del request si lo necesitas)
        $email = session('reset_email', $request->input('email'));

        // Si no hay email, redirige al login por seguridad
        if (!$email) {
            return redirect()->route('inicioSesion')->with('error', 'Acceso no autorizado.');
        }

        return view('auth.reset_password', ['email' => $email]);
    }

    public function actualizarPassword(Request $request)
    {
        // 1. Validar que las contraseñas sean iguales y tengan mínimo 6 caracteres
        $request->validate([
            'email' => 'required|email',
            'password1' => ['bail', 'required', 'string', 'min:6'],
            'password2' => ['bail', 'required', 'string', 'min:6', 'same:password1'],
        ], [
            'password1.min' => 'Las contraseñas deben ser de mínimo 6 caracteres.',
            'password2.min' => 'Las contraseñas deben ser de mínimo 6 caracteres.',
            'password2.same' => 'Las contraseñas no coinciden.',
        ]);

        // 2. Buscar el usuario por email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No se pudo actualizar la contraseña.');
        }

        // 3. Actualizar la contraseña
        $user->password = \Hash::make($request->password1);
        $user->save();

        // 4. Limpiar la sesión de recuperación
        session()->forget(['otp', 'otp_email', 'reset_email']);

        // 5. Redirigir al login con mensaje de éxito
        return redirect()->route('inicioSesion')->with('success', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
    }
}
