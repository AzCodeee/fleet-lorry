<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers'  => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $data = [
            "email"  => $request->input('email'),
            "source" => 'atlas'
        ];

        $url              = "/api/v1/atlas/login/password-reset-link";
        $response         = $client->post($url, ['json' => $data]);
        $http_status_code = $response->getStatusCode();

        if ($http_status_code == 200) {
            flash("The Reset Link has been sent to your email. Check your email.")->success();
            return redirect()->route('login');
        } elseif ($http_status_code == 422) {
            $message = json_decode($response->getBody()->getContents(), true);
            return back()->withInput()->withErrors(['email' => $message['message'] ?? 'Invalid request']);
        } else {
            return back()->withInput()->withErrors(['email' => 'Whoops, something went wrong. Error Code: ' . $http_status_code]);
        }
    }
}
