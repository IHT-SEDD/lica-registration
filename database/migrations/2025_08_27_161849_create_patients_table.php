<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('no_bpjs')->nullable();
            $table->string('nik');
            $table->string('medrec')->nullable();
            $table->string('name');
            $table->enum('gender', ['M', 'F']);
            $table->date('birthdate');
            $table->string('type_patient')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 17)->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
