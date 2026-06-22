<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\HttpClient;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class RefreshAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()) {
            $user = Auth::user();
            $request->session()->put('ACCESS_TOKEN', $user->access_token);

            $url              = "/api/v1/atlas/user/retrieve";
            $userResponse     = HttpClient::request('post', $url, ['user' => $user->email]);
            $userResponseCode = $userResponse->getStatusCode();
            $userContent      = json_decode($userResponse->getContent());
            if ($userResponseCode == 200 && isset(($userContent->data->token_expired_at)) && !empty($userContent->data->token_expired_at)) {
                $expiry  = strtotime($userContent->data->token_expired_at);
                $current = strtotime(date('Y-m-d H:i:s'));
                $balance = $expiry - $current;
                // renew token for the last 3mins(180secs) if there's activity 
                if ($balance <= 180) {
                    
                    $client = new Client([
                       'base_uri' => env('API_CLIENT_URL'),
                       'headers' => [
                           'Content-Type'  => 'application/json',
                           'Accept'        => 'application/json'
                       ],
                       'http_errors' => false
                    ]);

                    $renewData = [
                        'grant_type'    => 'password',
                        'client_id'     => env('API_CLIENT_ID'),
                        'client_secret' => env('API_CLIENT_SECRET'),
                        'username'      => $userContent->data->email,
                        'password'      => '@ero-4viat1on',
                        'scope'         => 'atlas messaging',
                    ];

                    $renewUrl        = '/oauth/token';
                    $renewResponse   = $client->request('post', $renewUrl, ['json' => $renewData]);
                    $renewStatusCode = $renewResponse->getStatusCode();
                    if ($renewStatusCode == 200) {
                        $renewed = json_decode($renewResponse->getBody()->getContents());

                        $request->session()->put('ACCESS_TOKEN', $renewed->access_token);

                        $renewClient = new Client([
                            'base_uri' => env('API_CLIENT_URL'),
                            'headers' => [
                                'Content-Type'  => 'application/json',
                                'Accept'        => 'application/json',
                                'Authorization' => 'Bearer ' . $renewed->access_token
                            ],
                            'http_errors' => false
                        ]);    
                    
                        $renewedData = [
                            'expires_in'    => $renewed->expires_in,
                            'access_token'  => $renewed->access_token,
                            'refresh_token' => $renewed->refresh_token
                        ];

                        $url               = "/api/v1/atlas/user/update-token-info/{$userContent->data->id}";
                        $renewResponse     = $renewClient->request('post', $url, ['json' => $renewedData]);
                        $renewResponseCode = $renewResponse->getStatusCode();
                        if ($renewResponseCode == 200) {
                            $content = json_decode($renewResponse->getBody()->getContents());
                            $user = User::where('created_at', '>=', Carbon::now()->subMinutes(60))->first();
                            $user->update([
                                'name'         => $content->data->name,
                                'email'        => $content->data->email,
                                'access_token' => $content->data->access_token,
                                'source'       => 'Atlas - Refreshed'
                            ]);

                            if (!empty($userContent->data->profile->photo_url)) {
                                $url        = "/api/v1/atlas/profile/download/{$userContent->data->profile->id}";
                                $response   = json_decode(HttpClient::download($url, $userContent->data->profile->id)->getContent());
                                if (isset($response->status_code) && $response->status_code == 200) {
                                    $image = file_get_contents($response->path);
                                }
                            }

                            $token['name']              =  $userContent->data->name;
                            $token['first_name']        =  $userContent->data->profile->first_name;
                            $token['last_name']         =  $userContent->data->profile->last_name;
                            $token['email']             =  $userContent->data->email;
                            $token['staff_id']          =  $userContent->data->profile->staff->id;
                            $token['id']                =  $userContent->data->id;
                            $token['image']             =  $image ?? null;
                            $token['hashed_user_id']    = Crypt::encryptString($userContent->data->id);
                            $token['hashed_profile_id'] = Crypt::encryptString($userContent->data->profile->id);
                            $token['hashed_staff_id']   = Crypt::encryptString($userContent->data->profile->staff->id);
                            session()->put(['current_token' => $token]);
                            session()->regenerate();
                        }
                    }
                }
            }           
        }
        
        return $next($request);
    }
}
