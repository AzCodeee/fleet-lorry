<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HttpClient
{
    /**
     * Request to API
     */
    public static function request($method, $url, $json = null)
    {
        $access_token_session = Session::get('ACCESS_TOKEN');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;

        if (empty($access_token)) {
            if (isset($_COOKIE['access_token']) && !empty($_COOKIE['access_token'])) {
                $controller   = new Controller;
                $access_token = $controller->decrypter($_COOKIE['access_token']);
            }
        }

        $client = new Client([
            'base_uri'    => env('API_CLIENT_URL'),
            'headers'     => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'http_errors' => false,
        ]);

        $response   = $client->request($method, $url, ['json' => $json]);
        $statusCode = $response->getStatusCode();

        if ($statusCode == 403) {
            flash('You are not authorized to access this resource.')->error();
            return redirect()->back()->withInput();
        }

        if ($statusCode == 404) {
            flash('The requested resource was not found (404).')->warning();
            return redirect()->back()->withInput();
        }

        if ($statusCode == 500) {
            flash('A server error occurred. Please contact the administrator.')->error();
            return redirect()->back()->withInput();
        }

        return response($response->getBody()->getContents(), $statusCode);
    }

    /**
     * Upload file to API
     */
    public static function upload($url, $form_data)
    {
        $access_token_session = Session::get('ACCESS_TOKEN');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;

        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers'  => [
                'Content-Type'  => 'multipart/form-data',
                'Authorization' => 'Bearer ' . $access_token,
            ],
        ]);

        $response = $client->request('POST', $url, $form_data);

        return response()->json(json_decode($response->getBody()->getContents()), $response->getStatusCode());
    }

    /**
     * Download file from API
     */
    public static function download($url, $filename)
    {
        $access_token_session = Session::get('access_token');
        $access_token = isset(Auth::user()->access_token) ? Auth::user()->access_token : $access_token_session;
        $client = new Client([
            'base_uri' => env('API_CLIENT_URL'),
            'headers'  => [
                'Authorization' => 'Bearer ' . $access_token,
            ],
        ]);

        $temp     = tempnam(sys_get_temp_dir(), $filename . '_');
        $resource = fopen($temp, 'w');

        try {
            $response    = $client->request('GET', $url, ['sink' => $resource]);
            $status_code = $response->getStatusCode();
            $name        = $response->getHeader('File-Name');
            $data        = (!empty($name))
                ? ['path' => $temp, 'name' => $name[0], 'status_code' => $status_code]
                : ['path' => $temp, 'name' => $filename, 'status_code' => $status_code];

            return response()->json($data);
        } catch (ClientException $e) {
            $status_code = $e->getResponse()->getStatusCode();
            flash('File download failed. Error Code: ' . $status_code)->error();
            return redirect()->back()->withInput();
        } catch (ServerException $e) {
            $status_code = $e->getResponse()->getStatusCode();
            flash('Server error during file download. Error Code: ' . $status_code)->error();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Paginate a collection
     */
    public static function paginate($items, $total, $per_page, $current_page)
    {
        return new LengthAwarePaginator(
            $items, $total, $per_page, $current_page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}