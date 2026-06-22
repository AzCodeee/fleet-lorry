<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\HttpClient;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use ThrottlesLogins;

    protected $api_module_profile;

    public function __construct()
    {
        $this->api_module_profile = '/api/v1/atlas/profile';
    }
    /**
     * Display the login view.
     */
    public function username()
    {
        return 'email';
    }

    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $client = new Client([
           'base_uri' => env('API_CLIENT_URL'),
           'headers' => [
               'Content-Type'  => 'application/json',
               'Accept'        => 'application/json'
           ],
           'http_errors' => false
        ]);

        $authData = [
            'grant_type'    => 'password',
            'client_id'     => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
            'username'      => $request->input('email'),
            'password'      => $request->input('password'),
            'scope'         => 'atlas messaging',
        ];

        $authUrl        = '/oauth/token';
        $authResponse   = $client->request('post', $authUrl, ['json' => $authData]);
        $authStatusCode = $authResponse->getStatusCode();
        if ($authStatusCode == 200) {
            $authenticated = json_decode($authResponse->getBody()->getContents());

            $data = [
                'source'        => 'web',
                'expires_in'    => $authenticated->expires_in,
                'access_token'  => $authenticated->access_token,
                'refresh_token' => $authenticated->refresh_token
            ];

            //set session access token
            $request->session()->put('ACCESS_TOKEN', $authenticated->access_token);

            $url              = "/api/v1/atlas/login/validate-token";
            $response         = HttpClient::request('post', $url, $data);
            $http_status_code = $response->getStatusCode();
            $body             = json_decode($response->getContent());
            if ($http_status_code == 200 && !empty($body->data->access_token) && !empty($body->data->user->profile->staff->id)) {
                //store login in db
                $user = new User;
                $user->name          = $body->data->user->name;
                $user->email         = $body->data->user->email;
                $user->access_token  = $body->data->access_token;
                $user->source        = 'Atlas';
                // $user->refresh_token = $body->refresh_token;
                $user->save();
                $auth = Auth::login($user);

                if (!empty($body->data->user->profile->photo_url)) {
                    $url        = "$this->api_module_profile/download/{$body->data->user->profile->id}";
                    $response   = json_decode(HttpClient::download($url,$body->data->user->profile->id)->getContent());

                    if (isset($response->status_code) && $response->status_code == 200) {
                        $image = file_get_contents($response->path);
                    } else {
                        flash()->warning('Failed to retrieve the profile image. Please update profile image.');
                    }
                }

                $token['name']               =  $body->data->user->name;
                $token['first_name']         =  $body->data->user->profile->first_name;
                $token['last_name']          =  $body->data->user->profile->last_name;
                $token['email']              =  $body->data->user->email;
                $token['staff_id']           =  $body->data->user->profile->staff->id;
                $token['id']                 =  $body->data->user->id;
                $token['image']              =  $image ?? null;
                $token['hashed_user_id']     = Crypt::encryptString($body->data->user->id);
                $token['hashed_profile_id']  = Crypt::encryptString($body->data->user->profile->id);
                $token['hashed_staff_id']    = Crypt::encryptString($body->data->user->profile->staff->id);
                session()->put(['current_token' => $token]);
                session()->regenerate();

               return redirect()->route('dashboard');

           } elseif(isset($body->data->password_expiry) && $body->data->password_expiry === TRUE) {
               flash("Whoops! You your need to reset password. Your previous password already expired.")->warning();
               return redirect()->route('password.expired.create');

           } elseif(isset($body->data->otp_sending_email) && $body->data->otp_sending_email === 'Success') {
               flash()->success('Please Check Your Email for One Time Verification Code');
               return redirect()->route('user.2fa', ['email' => $body->data->user->email]);
           } elseif(isset($body->data->otp_sending_email) && $body->data->otp_sending_email === 'Fail') {
               flash("Whoops! Something when wrong, Please contact your administrator.")->warning();
               return back()->withInput();
           } else {
               flash("Whoops! You don't have the role to login. Please contact your administrator.")->warning();
               return back()->withInput();
           }
       } elseif ($authStatusCode == 400) {
           $this->incrementLoginAttempts($request);
           flash("The credentials that you've entered is incorrect.")->warning();
           return back()->withInput();
       } elseif ($authStatusCode == 401) {
           $this->incrementLoginAttempts($request);
            if (isset($responseData['error']) && !empty($responseData['error'])) {
                flash($responseData['error'])->warning();
                return back()->withInput();
            } else {
                flash("The credentials that you've entered is incorrect.")->warning();
                return back()->withInput();
           }
       } else {
           $this->incrementLoginAttempts($request);
           $message = json_decode($authResponse->getBody()->getContents());
           flash()->warning($message?->message ?? 'Something went wrong.');
           flash('Whoops something went wrong. Error Code: '.$authStatusCode)->error();
           return back()->withInput();
       }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $url                = "/api/v1/atlas/user/logout";
        $response           = HttpClient::request('post', $url);
        $logoutResponseCode = $response->getStatusCode();
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
