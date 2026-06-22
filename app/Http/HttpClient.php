<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HttpClient
{
    /**
     * Request to API
     *
     * @return json_decode object
     */
    public static function request($method, $url, $json = null)
    {
        $access_token_session = Session::get('ACCESS_TOKEN');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;
        // Get info from cookies if empty
        if (empty($access_token)) {
            if (isset($_COOKIE['access_token']) && !empty($_COOKIE['access_token'])) {
                $controller   = New Controller;
                $access_token = $controller->decrypter($_COOKIE['access_token']);
            }
        }

        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$access_token
            ],
            'http_errors' => false
        ]);

        $response = $client->request($method, $url, ['json' => $json]);

        if ($response->getStatusCode() == 403) {
            abort(403, 'You are not authorized to access this api resource.');
        }
        if ($response->getStatusCode() == 404) {
            abort(404, '404 API NOT FOUND');
        }
        if ($response->getStatusCode() == 500) {
            abort(500, 'Please contact api administrator!');
        }
        return response($response->getBody()->getContents(), $response->getStatusCode());
    }

    public static function upload($url, $form_data)
    {
        $access_token_session = Session::get('ACCESS_TOKEN');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;
        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers' => [
                'Content-Type' => 'multipart/form-data',
                'Authorization' => 'Bearer '.$access_token
            ],
        ]);
        $response = $client->request('POST', $url, $form_data);
        return response()->json(json_decode($response->getBody()->getContents()), $response->getStatusCode());
    }

    public static function download($url, $filename)
    {
        $access_token_session = Session::get('access_token');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;
        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers' => [
                'Authorization' => 'Bearer '.$access_token
            ],
        ]);
        $temp = tempnam(sys_get_temp_dir(), $filename.'_');
        $resource = fopen($temp, 'w');

        try {
            $response = $client->request('GET', $url, ['sink' => $resource]);
            $status_code = $response->getStatusCode();
            $name = $response->getHeader('File-Name');
            $data = (!empty($name)) ? ['path' => $temp, 'name' => $name[0], 'status_code' => $status_code] : ['path' => $temp , 'name' => $filename, 'status_code' =>  $status_code];
            return response()->json($data);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $status_code = $response->getStatusCode();
            return response()->json(['error' => $e->getMessage()], $status_code);
        } catch (ServerException $e) {
            $status_code = $e->getResponse()->getStatusCode();
            return response()->json(['error' => 'Server Error: ' . $e->getMessage()], $status_code);
        }
    }

    public static function paginate($items, $total, $per_page, $current_page)
    {
        return new LengthAwarePaginator(
            $items, $total, $per_page, $current_page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}
