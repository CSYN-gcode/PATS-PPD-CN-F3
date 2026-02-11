<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapidPreShipmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapid_pre_shipment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('preshipment_pkid')->comment ='ID from production_runcards(table)';
            $table->string('master_carton_no')->nullable();
            $table->string('item_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('parts_code')->nullable();
            $table->string('device_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('qty')->nullable();
            $table->string('package_category')->nullable();
            $table->string('package_qty')->nullable();
            $table->unsignedBigInteger('weighed_by')->nullable();
            $table->unsignedBigInteger('packed_by')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment ='0 - Pending, 1 - Mass Prod, 2 - Resetup, 3 - Done';
            $table->softDeletes()->comment ='0-Active, 1-Deleted';
            $table->timestamps();

            // Foreign Key
            $table->foreign('weighed_by')->references('id')->on('users');
            $table->foreign('packed_by')->references('id')->on('users');
            $table->foreign('checked_by')->references('id')->on('users');
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
        Schema::dropIfExists('rapid_pre_shipment_details');
    }
}
