<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapidPreShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapid_pre_shipments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('date')->nullable();
            $table->string('control_no')->nullable();
            $table->string('sales_cutoff')->nullable();
            $table->string('destination')->nullable();
            $table->string('category')->nullable();
            $table->string('station')->nullable();
            $table->string('shipment_date')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment ='0 - Pending, 1 - Mass Prod, 2 - Resetup, 3 - Done';
            $table->softDeletes()->comment ='0-Active, 1-Deleted';
            $table->timestamps();

            // Foreign Key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapid_pre_shipments');
    }
}
