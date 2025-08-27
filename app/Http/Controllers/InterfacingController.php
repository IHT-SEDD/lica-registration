<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InterfacingController extends Controller
{
    public function insert(Request $request)
    {
        $results = $request->input("lists");

        if ($results != null) {
            //foreach but data only one
            foreach ($results as $result) {

                // check if these results from QC or NOT
                $checkQCText = 'QC';
                if (str_contains($result['patientId'], $checkQCText)) {

                    // QC

                    $raw_no_lot = $result['patientId'];
                    $no_lot = substr($raw_no_lot, 7);
                    $analyzer_id = $result['analyzerId'];

                    $transactionQC_query = DB::table('qc_transactions')
                        ->select('qc_transactions.*', 'qc_references.no_lot')
                        ->leftJoin('qc_references', 'qc_transactions.qc_reference_id', '=', 'qc_references.id')
                        ->where('qc_references.no_lot', $no_lot);
                    $transactionQC = $transactionQC_query->first();
                    // print_r($transactionQC);
                    // die;

                    if ($transactionQC) {

                        $qc_transaction_id = $transactionQC->id;
                        $qc_trans_tests = DB::table('qc_transaction_tests')
                            ->where('qc_transaction_id', $qc_transaction_id)
                            ->get();

                        foreach ($result['results'] as $test) {
                            $interfacing_test = DB::table('interfacings')->where('code', $test['name'])->where('analyzer_id', $analyzer_id)->get();
                            $countInterfacingData = $interfacing_test->count();

                            if ($countInterfacingData > 0) {
                                foreach ($interfacing_test as $interfacing_data) {
                                    foreach ($qc_trans_tests as $transaction_test_data) {
                                        if ($interfacing_data->test_id == $transaction_test_data->test_id) {

                                            $test_id = $interfacing_data->test_id;

                                            // reference Data
                                            $qc_reference_id = $transactionQC->qc_reference_id;
                                            $query_reference_query = DB::table('qc_reference_datas')
                                                ->where('qc_reference_id', $qc_reference_id)
                                                ->where('test_id', $test_id);
                                            $referenceData = $query_reference_query->first();

                                            // print_r($referenceData);
                                            // die;

                                            $targetValue = $referenceData->target_value;
                                            $deviation = $referenceData->deviation;
                                            $qc_value = $test['result'];

                                            // find position value 
                                            $position = ($qc_value - $targetValue) / $deviation;
                                            $position_value = round($position, 2);

                                            DB::table('qc_transaction_tests')
                                                ->where('test_id', $test_id)
                                                ->where('qc_transaction_id', $qc_transaction_id)
                                                ->update([
                                                    'value' => $qc_value,
                                                    'position' => $position_value,
                                                    'updated_at' => Carbon::now()->toDateTimeString()
                                                ]);
                                        }
                                    }
                                }
                            } else {
                                // echo "tidak ada interfacing data" . "<br>";
                            }
                        }
                    } else {
                        return response()->json('No Lot or patientId Not Found');
                    }
                } else {

                    // NOT QC

                    $lab_no = $result['patientId'];
                    $customDate = date('ym');

                    if (substr($lab_no, 0, 4) != $customDate) {
                        $lab_no = $customDate . substr($lab_no, 4);
                    }
                    // if (substr($lab_no, 0, 4) === '2505') {
                    //     $lab_no = '2504' . substr($lab_no, 4);
                    // }
                    $analyzer_id = $result['analyzerId'];
                    // $transaction = DB::table('transactions')->where('no_lab', $lab_no)->first();
                    $transaction = DB::table('transactions')
                        ->select('patients.gender', 'patients.birthdate', 'transactions.*', 'rooms.auto_draw')
                        ->leftJoin('patients', 'transactions.patient_id', '=', 'patients.id')
                        ->leftJoin('rooms', 'transactions.patient_id', '=', 'patients.id')
                        ->where('no_lab', $lab_no)->first();

                    if ($transaction) {
                        $born = Carbon::createFromFormat('Y-m-d', $transaction->birthdate);
                        $ageInDays = Carbon::createFromFormat('Y-m-d', $transaction->birthdate)->diffInDays(Carbon::now());
                        $birthdate = $born->diff(Carbon::now())->format('%yY / %mM / %dD');
                        $birthday = $born->diff(Carbon::now())->days;

                        $transaction_id = $transaction->id;
                        $trans_tests = DB::table('transaction_tests')
                            ->where('transaction_id', $transaction_id)
                            ->get();

                        foreach ($result['results'] as $test) {

                            $interfacing_test = DB::table('interfacings')->where('code', $test['name'])->where('analyzer_id', $analyzer_id)->get();
                            $countInterfacingData = $interfacing_test->count();

                            if ($countInterfacingData > 0) {
                                foreach ($interfacing_test as $interfacing_data) {
                                    foreach ($trans_tests as $transaction_test_data) {
                                        if ($interfacing_data->test_id == $transaction_test_data->test_id) {

                                            $test_id = $interfacing_data->test_id;

                                            $result_value = $test['result'];

                                            //hardcode handle for previous version ok jenis kelamin/gender
                                            if ($transaction->gender == "L") {
                                                $transaction->gender = "M";
                                            } elseif ($transaction->gender == "P") {
                                                $transaction->gender = "F";
                                            }

                                            $range = \App\Range::where('test_id', $test_id)->where('min_age', '<=', $ageInDays)->where('max_age', '>=', $ageInDays)->first();
                                            if ($range) {
                                                $status = $this->checkResultStatus($transaction->gender, $range, $result_value);

                                                switch ($status) {
                                                    case 'normal':
                                                        $result_status = AnalyticController::RESULT_STATUS_NORMAL;
                                                        break;
                                                    case 'low':
                                                        $result_status = AnalyticController::RESULT_STATUS_LOW;
                                                        break;
                                                    case 'high':
                                                        $result_status = AnalyticController::RESULT_STATUS_HIGH;
                                                        break;
                                                    case 'critical':
                                                        $result_status = AnalyticController::RESULT_STATUS_CRITICAL;
                                                        break;
                                                    case 'abnormal':
                                                        $result_status = AnalyticController::RESULT_STATUS_ABNORMAL;
                                                    default:
                                                        $result_status = 0;
                                                }
                                                $test = DB::table('tests')->select('tests.*', 'prices.id as price_id')->leftJoin('prices', 'prices.test_id', '=', 'tests.id')->where('tests.id', $test_id)->first();

                                                $check_format_number = \App\Test::where('id', $test_id)->first();
                                                $format_number = $check_format_number->format_decimal;

                                                /*check type*/
                                                if ($test->range_type == 'number') {

                                                    // check format number
                                                    if ($format_number != NULL) {
                                                        if ($format_number == 1) {
                                                            if ($result_value != '') {
                                                                $result = number_format($result_value, 1, '.', ',');
                                                            } else {
                                                                $result = $result_value;
                                                            }
                                                        } elseif ($format_number == 2) {
                                                            if ($result_value != '') {
                                                                $result = number_format($result_value, 2, '.', ',');
                                                            } else {
                                                                $result = $result_value;
                                                            }
                                                        } elseif ($format_number == 3) {
                                                            if ($result_value != '') {
                                                                $result = number_format($result_value, 3, '.', ',');
                                                            } else {
                                                                $result = $result_value;
                                                            }
                                                        } elseif ($format_number == 4) {
                                                            if ($result_value != '') {
                                                                $result = number_format($result_value, 4, '.', ',');
                                                            } else {
                                                                $result = $result_value;
                                                            }
                                                        } elseif ($format_number == 404) {
                                                            if (strpos($result_value, ".") !== false) {
                                                                $result = $result_value;
                                                            } else {
                                                                // ribuan
                                                                $result_value = number_format($result_value);
                                                                $result = $result_value;
                                                            }
                                                        }
                                                    } else {

                                                        if (strlen($result_value) >= 4) {
                                                            // bukan ribuan
                                                            if (strpos($result_value, ".") !== false) {
                                                                $result = (int)$result_value;
                                                                $result = number_format($result);
                                                            } else {

                                                                if (strpos($result_value, ".") !== false) {
                                                                    $result = $result_value;
                                                                } else {
                                                                    // ribuan
                                                                    $result_value = number_format($result_value);
                                                                    $result = $result_value;
                                                                }
                                                            }
                                                        } else {
                                                            if (strpos($result_value, ".") !== false) {
                                                                $result = (int)$result_value;
                                                            } else {
                                                                $result = $result_value;
                                                            }
                                                        }
                                                    }

                                                    DB::table('transaction_tests')
                                                        ->where('test_id', $test_id)
                                                        ->where('transaction_id', $transaction_id)
                                                        ->where('mark_duplo', 0)
                                                        ->update([
                                                            'result_number' => $result,
                                                            'result_status' => $result_status,
                                                            'input_time' => Carbon::now()->toDateTimeString()
                                                        ]);
                                                } elseif ($test->range_type == 'label') {
                                                    // on development
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                echo "tidak ada interfacing data" . "<br>";
                            }
                        }
                    } else {
                        return response()->json('No Lab or patients Not Found');
                    }
                }
            }
            return response()->json(1);
        } else {
            return response()->json('No Results');
        }
    }

    private function checkResultStatus($gender, $range, $result)
    {
        $status = '';
        if ($gender == 'M') {
            if ($result >= $range->min_male_ref && $result <= $range->max_male_ref) {
                $status = 'normal';
            } else if ($result < $range->min_crit_male || $result > $range->max_crit_male) {
                $status = 'critical';
            } else if ($result < $range->min_male_ref) {
                $status = 'low';
            } else if ($result > $range->max_male_ref) {
                $status = 'high';
            }
        } else {
            if ($result >= $range->min_female_ref && $result <= $range->max_female_ref) {
                $status = 'normal';
            } else if ($result < $range->min_crit_female || $result > $range->max_crit_male) {
                $status = 'critical';
            } else if ($result < $range->min_female_ref) {
                $status = 'low';
            } else if ($result > $range->max_female_ref) {
                $status = 'high';
            }
        }

        return $status;
    }

    public function getPatient($no_lab = null)
    {
     try {
        $query_patient = DB::table('transactions')
        ->selectRaw('transactions.*, transaction_tests.transaction_id, master_patients.gender, master_patients.birth, master_patients.name, master_patients.medrec')
        ->leftJoin('transaction_tests', 'transaction_tests.transaction_id', '=' ,'transactions.id')
        ->leftJoin('master_patients', 'transactions.master_patient_id', '=' ,'master_patients.id')
        ;
        if($no_lab != null){
            $query_patient->where('no_lab', '=' ,$no_lab);
        }
        $query_patient->where('transactions.status', '=' , 2);
        $data_patient = $query_patient->first();

      
        $data = [
                'pasien' => $data_patient->name,
                'no_lab' => (int) $data_patient->no_lab,
                'medrec' => (string) $data_patient->medrec,
                'tgl_lahir' => $data_patient->birth,
                'jk' => $data_patient->gender,
        ];


        $query = DB::table('transaction_tests')
        ->selectRaw('transactions.id, transactions.status, transaction_tests.transaction_id, transaction_tests.master_test_id, interfacing_tests.interfacing_code')
        ->leftJoin('transactions', 'transaction_tests.transaction_id', '=' ,'transactions.id')
        ->leftJoin('interfacing_tests', 'transaction_tests.master_test_id', '=' ,'interfacing_tests.test_id')
        ;
        if($no_lab != null){
            $query->where('no_lab', '=' ,$no_lab)
            ->groupBy('transaction_tests.master_test_id');
        }
        $query->where('transactions.status', '=' , 2);
        $data_test = $query->get();

        foreach($data_test as $key => $value){
        $data_transaction_test[] = [
            'test_id' => $value->master_test_id,
            'interfacing_code' => $value->interfacing_code
        ]; 
         }

        $data['tests'] = $data_transaction_test;
       
      return response()->json($data);  
       
     } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()],400);
     }
    }


    public function getNoLab($analyzer_id)
    {
     try {

        $today = date('Y-m-d');
        
        $query_patient = DB::table('transactions')
        ->selectRaw('transactions.id, transactions.no_lab, patients.medrec, patients.name, patients.gender, patients.birthdate ,transactions.status, transaction_tests.analyzer_id, transaction_tests.test_id, interfacings.code')
        ->leftJoin('patients', 'patients.id', 'transactions.patient_id')
        ->leftJoin('transaction_tests', 'transaction_tests.transaction_id', 'transactions.id')
        ->leftJoin('interfacings', function ($join) {
            $join->on('transaction_tests.test_id', '=', 'interfacings.test_id');
            $join->on('transaction_tests.analyzer_id', '=', 'interfacings.analyzer_id');
        });

        $query_patient->where('transactions.status', '=' , 1)
                        ->whereBetween(DB::raw('DATE(transactions.created_time)'),[$today, $today] )
                        ->where('transaction_tests.analyzer_id', $analyzer_id)
                        ->where('transactions.status_interfacing',  0);
        $list_patients = $query_patient->groupBy('transactions.no_lab')->get();

        $list_data = [];
        foreach ($list_patients as $key => $patient) {
            $tests = [];
            $list_test = DB::table('transaction_tests')
                        ->selectRaw('transaction_tests.id, transaction_tests.analyzer_id, transaction_tests.test_id, interfacings.code')
                        ->leftJoin('interfacings', function ($join) {
                            $join->on('transaction_tests.test_id', '=', 'interfacings.test_id');
                            $join->on('transaction_tests.analyzer_id', '=', 'interfacings.analyzer_id');
                        })
                        ->where('transaction_tests.analyzer_id', $analyzer_id)
                        ->where('transaction_tests.transaction_id', $patient->id)
                        ->get();
           
            foreach ($list_test as $key => $test) {
                $tests[] = [
                    'id_trans' => $test->id,
                    'test_id' => $test->test_id,
                    "interfacing_code" => $test->code
                ];
            }

            $data = [
                'no_lab' => $patient->no_lab,
                'pasien' => $patient->name,
                'tgl_lahir' => $patient->birthdate,
                'medrec' => $patient->medrec,
                'jk' => $patient->gender,
                'tests' => $tests,
            ];

            $list_data[]=$data;
        }

      return response()->json($list_data);  
       
     } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()],400);
     }
    }


    public function syncInterfacing($no_lab)
    {
        try {

        $patient = DB::table('transactions')
            ->where('transactions.no_lab', $no_lab)
            ->first();

        if($patient){
            DB::table('transactions')
            ->where('transactions.no_lab', $no_lab)
            ->update([
                'status_interfacing' => 1
            ]);
        }

        return response()->json("Sukses !!!");
       
        } catch (\Exception $te) {
            return response()->json(['message' => $e->getMessage(), 500]);
        }
    }
}
