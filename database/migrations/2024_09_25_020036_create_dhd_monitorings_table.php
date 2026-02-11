<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhdMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhd_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('dhd_no');
            $table->string('device_name');
            $table->string('device_code');
            $table->string('mtl_name');
            $table->string('mtl_lot_virgin');
            $table->string('mtl_lot_recycle');
            $table->string('mtl_mix_virgin');
            $table->string('mtl_mix_recycle');
            $table->string('mtl_ttl_mixing');
            $table->string('mtl_dry_setting');
            $table->string('mtl_dry_actual');
            $table->string('mtl_dry_timeIn');
            $table->string('mtl_dry_timeOut');
            $table->string('dhd_ashift_actual_temp');
            $table->string('dhd_ashift_mtl_level');
            $table->string('dhd_ashift_time');
            $table->string('dhd_bshift_actual_temp')->nullable();
            $table->string('dhd_bshift_mtl_level')->nullable();
            $table->string('dhd_bshift_time')->nullable();
            $table->string('person_incharge');
            $table->string('qc_inspector');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedTinyInteger('logdel')->default(0)->comment = '0-active,1-inactive';
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
        Schema::dropIfExists('dhd_monitorings');
    }
}
