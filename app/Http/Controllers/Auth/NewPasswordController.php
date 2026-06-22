<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reset(Request $request)
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
            'base_uri'=> env('API_CLIENT_URL'),
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ],
            'http_errors'       => false
        ]);

        $data = [
            "hash_value"            => $request->input('token'),
            "password"              => $request->input('password'),
            "password_confirmation" => $request->input('password_confirmation'),
        ];

        $url                = "/api/v1/atlas/login/password-reset";
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
