<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDmrpqcProductIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmrpqc_product_identifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category')->default(1)->comment = '1 - FACTORY 1 & 2, 2 - FACTORY 3';
            $table->string('device_name');
            $table->string('po_number');
            $table->string('item_code');
            $table->string('die_no')->nullable();
            $table->string('drawing_no')->nullable();
            $table->string('rev_no')->nullable();
            $table->string('request_type')->nullable()->comment = '1 - For Maintenance, 2 - Temporary Stop';
            $table->string('start_date_time');
            $table->unsignedTinyInteger('status')->default(0)->comment = '0 - For Submission, 1 - For Conformance, 2 - Ongoing Condition Checking, 3 - Request Completed';
            $table->unsignedTinyInteger('process_status')->default(1)->comment = '1 - Product Identification, 2 - Dieset Condition, 3 - Dieset Condition Checking, 4 - Machine Setup, 5 - Product Requirement Checking, 6 - Machine Parameter Checking, 7 - Specifications, 8 - Completion Activity, 9 - Request Completed';

            // Defaults
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->tinyInteger('logdel')->default(0)->comment = '0-Active, 1-Deleted';
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
        Schema::dropIfExists('dmrpqc_product_identifications');
    }
}
