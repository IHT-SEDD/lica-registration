<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Transaction extends Model
{
    // protected $with = ['patient'];
    protected $fillable = [
        'patient_id',
        'nik',
        'room_id',
        'doctor_id',
        'insurance_id',
        'analyzer_id',
        'type',
        'no_lab',
        'note',
        'created_time',
        'cito',
        'transaction_id_label',
        'checkin_time',
        'status',
        'is_igd',
    ];

    public function patient()
    {
        return $this->belongsTo('App\Patient', 'patient_id', 'id');
    }


}
