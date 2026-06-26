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
use Illuminate\Support\Facades\Session;

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

        // ── Step 1: Authenticate against external API ──
        $authResponse = $client->request('post', '/api/auth/login', [
            'json' => [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ],
        ]);

        $authStatus = $authResponse->getStatusCode();
        $authBody   = json_decode($authResponse->getBody()->getContents(), true);

        // ── Step 2: Handle failed login ──
        if ($authStatus !== 200) {
            $message = $authBody['message'] ?? $authBody['error'] ?? "The credentials you've entered are incorrect.";
            return back()->withInput($request->only('email'))->withErrors(['email' => $message]);
        }

        // ── Step 3: Extract token and user — API returns { data: { token, user } } ──
        $apiData   = $authBody['data']         ?? [];
        $apiToken  = $apiData['token']         ?? $apiData['access_token'] ?? null;
        $apiUser   = $apiData['user']          ?? [];
        $userEmail = $apiUser['email']         ?? $request->input('email');
        $userName  = $apiUser['name']          ?? $userEmail;

        if (!$apiToken) {
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'Login failed: no token received from server.']);
        }

        // ── Step 4: Upsert local user row and save token to DB ──
        $user = User::updateOrCreate(
            ['email' => $userEmail],
            [
                'name'         => $userName,
                'password'     => Hash::make($request->input('password')),
                'access_token' => $apiToken,
            ]
        );

        // ── Step 5: Log the user in via Laravel session ──
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // ── Step 6: Store token in session — HttpClient reads SESSION::get('ACCESS_TOKEN') ──
        $request->session()->put('ACCESS_TOKEN', $apiToken);
        $access_token_session = Session::get('ACCESS_TOKEN');

        return redirect()->intended(route('dashboard'));
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