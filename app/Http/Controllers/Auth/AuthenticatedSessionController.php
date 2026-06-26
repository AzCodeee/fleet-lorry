<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $client = new Client([
            'base_uri'    => env('API_CLIENT_URL'),
            'headers'     => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $authResponse = $client->request('post', '/api/auth/login', [
            'json' => [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ],
        ]);

        $authStatus = $authResponse->getStatusCode();
        $authBody   = json_decode($authResponse->getBody()->getContents(), true);

        if ($authStatus === 200) {
            $token     = $authBody['token'] ?? $authBody['access_token'] ?? null;
            $apiUser   = $authBody['user'] ?? [];
            $userEmail = $apiUser['email'] ?? $request->input('email');
            $userName  = $apiUser['name']  ?? $userEmail;

            // Upsert local user row — password column is NOT NULL so we store
            // a random hash. The real auth is the API token, not this password.
            $user = User::updateOrCreate(
                ['email' => $userEmail],
                [
                    'name'     => $userName,
                    'password' => Hash::make($request->input('password')),
                ]
            );

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('ACCESS_TOKEN', $token);

            return redirect()->intended(route('dashboard'));
        }

        if ($authStatus === 401 || $authStatus === 400) {
            $message = $authBody['message'] ?? $authBody['error'] ?? "The credentials you've entered are incorrect.";
            return back()->withInput($request->only('email'))->withErrors(['email' => $message]);
        }

        $message = $authBody['message'] ?? "Something went wrong (HTTP {$authStatus}).";
        return back()->withInput($request->only('email'))->withErrors(['email' => $message]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}