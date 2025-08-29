<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class FormController extends Controller
{
    public function index()
    {
        return view('registration.index');
    }

    public function create(Request $request)
    {
        try{

            DB::beginTransaction();

             $data = $request->except(['_token', 'search_nik']);

            foreach ($data as $key => $value) {
                if($value == null){
                    unset($data[$key]);
                }
            }

            $validator  = Validator::make(
                $data,
                [
                    'nik'       => 'required|numeric',
                    'no_bpjs'   => 'digits_between:11,16',
                    'name'      => 'required|string|max:255',
                    'email'     => 'email',
                    'phone'     => 'required|string|numeric',
                    'birthdate' => 'required|date',
                    'gender'    => 'required|in:M,F',
                    'address'   => 'required|string|max:255',
                    'type_patient' => 'required|in:Lingkungan,Laboratorium',
                ]
            );

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

             $checkMedrec = DB::table('patients')
             ->select('medrec')
             ->where('type_patient', $data['type_patient'])
             ->orderBy('medrec', 'desc')
             ->first();

            if (empty($checkMedrec)) {
                // generate new medrec - format (CM.year (2angka).no_urut (5 angka))
                switch ($data['type_patient']) {
                    case 'Lingkungan':
                        $unit = 'LINGKUNGAN';
                        break;
                    case 'Laboratorium':
                        $unit = 'LABORATORIUM';
                    break;
                    default:
                        $unit = 'LABORATORIUM';
                        break;
                }
                $generateMedrec = $unit . date('y') . '00001';
            } else {
                switch ($data['type_patient']) {
                    case 'Lingkungan':
                        $temp = substr($checkMedrec->medrec, 7);
                        $unit = 'LINGKUNGAN';
                        break;
                    case 'Laboratorium':
                        $temp = substr($checkMedrec->medrec, 12);
                        $unit = 'LABORATORIUM';
                    break;
                    default:
                        $temp = substr($checkMedrec->medrec, 12);
                        $unit = 'LABORATORIUM';
                        break;
                }
                $medrec = $temp + 1;
                $generateMedrec = $unit . $medrec;

            }

            $data['medrec'] = $generateMedrec;

            $patient = \App\Patient::where('nik', $data['nik'])->first();

            if(!$patient){
                $patient = \App\Patient::create($data);
            }

            \App\Transaction::create([
                'patient_id' => $patient->id,
                'nik' => $patient->nik,
                'type_patient' => $patient->type_patient,
                'created_time' => now()
            ]);

            DB::commit();

            return response()->json(['message' => 'Pendaftaran berhasil', 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function checkNIK($nik)
    {
        $data = \App\Patient::where('nik', $nik)->first();

        $data ? $status = 200 : $status = 400;

        return response()->json(['data' => $data, 'status' => $status]);
    }
}
