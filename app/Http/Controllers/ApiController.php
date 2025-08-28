<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiController extends Controller
{

    protected function authCheck($key)
    {

        $query = DB::table('master_auth_api')->where('api', 'simrs');
        $api_key = $query->first();
        if ($api_key) {
            if ($key == $api_key->key) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function responseWithError($code, $message)
    {
        return response()->json([
            'error' => [
                'message' => $message,
                'status_code' => $code
            ]
        ]);
    }

    public function successResponse()
    {
        return response()->json([
            'success' => [
                'message' => 'Data transaksi berhasil sinkronisasi',
                'status_code' => 200
            ]
        ]);
    }

    public function successResponseUpdate()
    {
        return response()->json([
            'success' => [
                'message' => 'Data tes berhasil ditambahkan',
                'status_code' => 200
            ]
        ]);
    }

    public function transactions()
    {
        try{

            \App\Transaction::where('status', '0')->get();

        }catch(\Exception $e){
            return $this->responseWithError(500, $e->getMessage());
        }
    }

}
