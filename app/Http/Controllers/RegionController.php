<?php

namespace App\Http\Controllers;

use App\Http\HttpClient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
{ 
    protected $api_module;
  
    public function __construct()
    {
        $this->api_module = '/api/regions'; 
    }

    /**
     * Controller index - get reports and attach stage, color, actions
     */
    public function index(Request $request)
    {
        $query_params   = $request->query();
        ksort($query_params);

        $query_string   = Http_build_query($query_params);
        $url            = (!empty($query_string) ? "{$this->api_module}?{$query_string}" : "$this->api_module");
        $response       = json_decode(HttpClient::request('get', $url)->getContent());
        $region    = HttpClient::paginate($response->data, $response->meta->total, $response->meta->per_page, $response->meta->current_page);

        return view('regions.index', compact('region'));
    }

    public function create(Request $request)
    {

        return view('settings.audit-setup.base.create');
    }
    
    public function show($id)
    {
        $url        = "$this->api_module/{$id}";
        $response   = json_decode(HttpClient::request('get', $url)->getContent());
        $base = $response->data;

        return view('settings.audit-setup.base.show', compact(
            'base'
        ));

    }

    public function edit($id)
    {
        $url        = "$this->api_module/{$id}";
        $response   = json_decode(HttpClient::request('get', $url)->getContent());
        $base = $response->data;

        return view('settings.audit-setup.base.edit', compact(
            'base'
        ));
    }

    public function store(Request $request)
    {
        $url      = "$this->api_module";
        $response = HttpClient::request('post', $url, $request->all());

        if ($response->getStatusCode() == 201) {
            flash()->success('Audit Area has been created.');
            return redirect()->route('settings.audit-setup.base.index');
        } else {
        	flash()->warning('Fail to create Audit Area! Please try again.');
            return back()->withInput();
        }
    }

    public function update(Request $request , $id)
    {
        $url        = "$this->api_module/{$id}";
        $response   = HttpClient::request('put', $url, $request->all());

        if ($response->getStatusCode() == 200) {
        	flash()->success('Audit Area details has been updated.');
            return redirect()->route('settings.audit-setup.base.index');
        } else {
        	flash()->warning('Fail to update Audit Area! Please try again.');
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $url        = "$this->api_module/{$id}";
        $response   = HttpClient::request('delete', $url);

        if ($response->getStatusCode() == 200) {
        	flash()->success('Audit Area has been deleted.');
            return redirect()->route('settings.audit-setup.base.index');
        } else {
        	flash()->warning('Fail to delete Audit Area! Please try again.');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
}
