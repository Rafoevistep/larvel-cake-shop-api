<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending','processing','completed','decline'])->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->enum('payment_method', ['cash_on_delivery'])->default('cash_on_delivery');
            $table->string('flat');
            $table->string('street_name');
            $table->string('area');
            $table->string('landmark');
            $table->string('city');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
