<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('runcard_id')->comment = 'Runcard ID';
            $table->string('po_no');
            $table->string('po_received_qty');
            $table->unsignedTinyInteger('lot_category')->comment = '1 = Special Case';
            $table->string('lot_no');
            $table->string('lot_no_ext');
            $table->float('actual_so');
            $table->string('package_category');
            $table->string('remarks')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('delivery_updates');
    }
}
