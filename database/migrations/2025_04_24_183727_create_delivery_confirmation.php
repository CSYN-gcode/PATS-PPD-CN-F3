<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryConfirmation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_confirmation', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('POrcv_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_code')->nullable();
            $table->string('order_no')->nullable();
            $table->string('shipment_date')->nullable();
            $table->bigInteger('order_balance')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('delivery_confirmation');
    }
}
