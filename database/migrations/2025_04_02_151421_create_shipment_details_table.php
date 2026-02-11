<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipment_id')->nullable();   
            $table->string('fkControlNo');
            $table->string('order_no');
            $table->string('lot_no');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('shipout_qty');
            $table->float('unit_price');
            $table->float('amount');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('logdel')->default(0)->comment = '0-active,1-inactive';

            $table->foreign('shipment_id')->references('id')->on('shipments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_details');
    }
}
