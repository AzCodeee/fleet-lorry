<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function showResetExpiredForm()
    {
        return view('auth.reset-expired');
    }

    public function resetExpired(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:12|confirmed',
        ]);

        $password = $request->input('password');

        $errors = [];

        // Check each condition manually
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'The password must include at least one uppercase letter.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'The password must include at least one lowercase letter.';
        }

        if (!preg_match('/\d/', $password)) {
            $errors[] = 'The password must include at least one digit.';
        }

        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = 'The password must include at least one special character.';
        }

        if (!empty($errors)) {
            return back()->withErrors(['password' => $errors]);
        }

        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers'  => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ],
            'http_errors'       => false
        ]);

        $data = [
            "email"                 => $request->input('email'),
            "current_password"      => $request->input('current_password'),
            "password"              => $request->input('password'),
            "password_confirmation" => $request->input('password_confirmation'),
        ];

        $url                = "/api/v1/atlas/login/update-password-expiry";
        $response           = $client->request('post', $url, ['json' => $data]);
        $http_status_code   = $response->getStatusCode();

        if ($http_status_code == 200) {
            flash()->success('The password had been reset. Please Proceed to Login.');
            return redirect()->route('login');
        } elseif($http_status_code == 422) {
            $message = json_decode($response->getBody()->getContents());
            flash()->warning($message->message);
            return back()->withInput();
        } else {
            flash('Whoops something went wrong. Error Code: '. $http_status_code)->error();
            return back()->withInput();
        }
    }
}
