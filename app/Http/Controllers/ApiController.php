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

        $data = \App\Transaction::with(['patient'])->where('status', '0')
                ->whereDate('created_time', now())
                ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data transaksi',
                'status'  => 404,
                'data'    => null,
            ]);
        }

        return response()->json(['message' => 'Data transaksi berhasil diambil', 'status' => 200, 'data' => $data]);

        }catch(\Exception $e){
            return $this->responseWithError(500, $e->getMessage());
        }
    }

        public function updateTransactions(Request $request)
    {
         try{

             if($request['status'] == 200)
            {
                 \App\Transaction::whereIn('id', $request['lists'])->update(['status' => 1]);
                return $this->successResponseUpdate();
            }

            return $this->responseWithError(500, 'Server Error');

        }catch(\Exception $e){
            return $this->responseWithError(500, $e->getMessage());
        }

    }

}
