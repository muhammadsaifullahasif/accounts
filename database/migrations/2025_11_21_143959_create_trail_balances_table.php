<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trail_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->string('account_code');
            $table->string('account_head');
            $table->string('group_code');
            $table->string('group_name');
            $table->string('opening_debit')->nullable();
            $table->string('opening_credit')->nullable();
            $table->string('movement_debit')->nullable();
            $table->string('movement_credit')->nullable();
            $table->string('closing_debit')->nullable();
            $table->string('closing_credit')->nullable();
            $table->bigInteger('modified_by')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trail_balances');
    }
};
