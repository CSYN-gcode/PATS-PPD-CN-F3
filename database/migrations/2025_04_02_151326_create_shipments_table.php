<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('ctrl_no');
            $table->string('ps_ctrl_no');
            $table->string('shipment_date');
            $table->unsignedTinyInteger('rev_no')->default(0);
            $table->string('sold_to');
            $table->string('shipped_by');
            $table->string('cutoff_month');
            $table->float('grand_total');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('logdel')->default(0)->comment = '0-active,1-inactive';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}
