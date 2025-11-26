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
        Schema::create('fixed_assets_scheduals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->string('account_code')->nullable();
            $table->string('account_head')->nullable();
            $table->string('opening')->nullable();
            $table->string('addition')->nullable();
            $table->string('addition_no_of_days')->nullable();
            $table->string('deletion')->nullable();
            $table->string('deletion_no_of_days')->nullable();
            $table->string('closing')->nullable();
            $table->string('rate')->nullable();
            $table->string('depreciation_account_code')->nullable();
            $table->string('depreciation_account_head')->nullable();
            $table->string('depreciation_opening')->nullable();
            $table->string('depreciation_addition')->nullable();
            $table->string('depreciation_deletion')->nullable();
            $table->string('depreciation_closing')->nullable();
            $table->string('wdv')->nullable();
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
        Schema::dropIfExists('fixed_assets_scheduals');
    }
};
