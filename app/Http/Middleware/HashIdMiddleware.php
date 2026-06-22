<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class HashIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('id')) {
            try {
                $decryptedId = Crypt::decryptString($request->route('id'));
                $request->route()->setParameter('id', $decryptedId);
            } catch (\Exception $e) {
                abort(404);
            }
        }

        if ($request->query('user_id')) {
            try {
                $ecryptedUserId  = $request->query('user_id');
                $decryptedUserId = Crypt::decryptString($request->query('user_id'));
                if ($request->route('user_id')) {
                    $request->route()->setParameter('user_id', $decryptedUserId);
                    $request->query->set('user_id_encrypted', $ecryptedUserId);
                } else {
                    $request->query->set('user_id', $decryptedUserId);
                    $request->query->set('user_id_encrypted', $ecryptedUserId);
                }
            } catch (\Exception $e) {
                abort(404);
            }
        }

        if ($request->query('staff')) {
            try {
                $encryptedStaff = $request->query('staff');
                $decryptedStaff = Crypt::decryptString($request->query('staff'));
                if ($request->route('staff')) {
                    $request->route()->setParameter('staff', $decryptedStaff);
                    $request->query->set('staff_encrypted', $encryptedStaff);
                } else {
                    $request->query->set('staff', $decryptedStaff);
                    $request->query->set('staff_encrypted', $encryptedStaff);
                }
            } catch (\Exception $e) {
                abort(404);
            }
        }

        if ($request->query('id')) {
            try {
                $decryptedid = Crypt::decryptString($request->query('id'));
                if ($request->route('id')) {
                    $request->route()->setParameter('id', $decryptedid);
                } else {
                    $request->query->set('id', $decryptedid);
                }
            } catch (\Exception $e) {
                abort(404);
            }
        }

        $response = $next($request);

        // if ($response instanceof JsonResponse) {
        //     $data = $response->getData(true);
        //     if (isset($data['data']['id'])) {
        //         $data['data']['id'] = Crypt::encryptString($data['data']['id']);
        //         $response->setData($data);
        //     }
        // }
        return $response;
    }
}
