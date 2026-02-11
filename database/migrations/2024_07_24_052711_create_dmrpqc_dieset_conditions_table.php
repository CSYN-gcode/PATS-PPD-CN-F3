<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDmrpqcDiesetConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dmrpqc_dieset_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_id')->comment = 'id from dmrpqc_product_identifications';
            $table->unsignedTinyInteger('action_1_mold_cleaned')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_2_mold_check')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_3_device_conversion')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_4_dieset_overhaul')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_4a_fix_side')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_4b_movement_side')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_4c_with_parts_marking')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_4d_without_parts_marking')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_5_reversible_parts_installed')->nullable()->comment = '0 - No, 1 - Yes';
            $table->unsignedTinyInteger('action_6_repair')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_7_parts_change')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_7a_new')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_7b_fabricated')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_7c_with_parts_marking')->nullable()->comment = '1 - Checked';
            $table->unsignedTinyInteger('action_7d_with_parts_change_notice')->nullable()->comment = '1 - Checked';
            $table->string('details_of_activity')->nullable();
            $table->string('parts_no')->nullable();
            $table->string('quantity')->nullable();
            $table->string('action_done_date_start')->nullable();
            $table->string('action_done_start_time')->nullable();
            $table->string('action_done_date_finish')->nullable(); //new column
            $table->string('action_done_finish_time')->nullable();
            $table->unsignedBigInteger('in_charged')->nullable();
            $table->string('check_point_1_marking_check')->nullable();
            $table->string('check_point_2_tanshi_pin')->nullable();
            $table->string('check_point_2a_crack')->nullable();
            $table->string('check_point_2b_bend')->nullable();
            $table->string('check_point_2c_worn_out')->nullable();
            $table->string('check_point_3_dent')->nullable();
            $table->string('check_point_4_porous')->nullable();
            $table->string('check_point_5_ejector_pin')->nullable();
            $table->string('check_point_6_coma')->nullable();
            $table->string('check_point_7_gasvent')->nullable();
            $table->string('check_point_8_assy_orientation')->nullable();
            $table->string('check_point_9_fs_ms_fitting')->nullable();
            $table->string('check_point_10_sub_gate')->nullable();
            $table->string('check_point_remarks')->nullable();
            $table->string('mold_check_1_withdraw_pin_external')->nullable();
            $table->string('mold_check_2_withdraw_pin_internal')->nullable();
            $table->string('mold_check_3_slidecore_stopper')->nullable();
            $table->string('mold_check_4_locator_ring')->nullable();
            $table->string('mold_check_5_bolts_nuts')->nullable();
            $table->string('mold_check_6_stripper_plate')->nullable();
            $table->string('mold_check_remarks')->nullable();
            $table->unsignedBigInteger('mold_check_checked_by')->nullable();
            $table->string('mold_check_date')->nullable();
            $table->string('mold_check_time')->nullable();
            $table->string('mold_check_status')->nullable();
            $table->string('parts_drawing')->nullable();
            $table->string('drawing_specification')->nullable();
            $table->string('drawing_actual_measurement')->nullable();
            $table->unsignedBigInteger('drawing_fabricated_by')->nullable();
            $table->unsignedBigInteger('drawing_validated_by')->nullable();
            $table->unsignedTinyInteger('references_used')->nullable()->comment = '1 - Eval. Sample, 2 - Japan Sample, 3 - Dieset Assy Drawing, 4 - Last Production Sample';
            $table->unsignedTinyInteger('final_remarks')->nullable()->comment = '1 - No Problem, 2 - With Problem Found';

            // Defaults
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment = '0-Unchanged, 1-Updated';
            $table->tinyInteger('logdel')->default(0)->comment = '0-Active, 1-Deleted';
            $table->timestamps();

            // Foreign Key
            $table->foreign('request_id')->references('id')->on('dmrpqc_product_identifications');
            $table->foreign('in_charged')->references('id')->on('users');
            $table->foreign('drawing_fabricated_by')->references('id')->on('users');
            $table->foreign('drawing_validated_by')->references('id')->on('users');
            $table->foreign('mold_check_checked_by')->references('id')->on('users');
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
        Schema::dropIfExists('dmrpqc_dieset_conditions');
    }
}
