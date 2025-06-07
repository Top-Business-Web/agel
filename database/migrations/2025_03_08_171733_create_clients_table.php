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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('national_id');
            $table->boolean('status')->default(1);
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('image')->nullable();

            $table->string('note')->nullable();
            $table->string('bgCover')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.`
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
