<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_payment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('service_invoice_id')->nullable();
            $table->integer('current_paid_amount')->nullable();
            $table->string('payment_option')->nullable();
            $table->date('date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('location_id')->nullable();
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
        Schema::dropIfExists('service_payment_details');
    }
};
