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
        Schema::create('stocks', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->foreignId('branch_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
//            $table->foreignId('investor_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
//            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->cascadeOnDelete()->cascadeOnUpdate();
//            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
//            $table->string('qty');
//            $table->string('price')->comment('price per unit');

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
        Schema::dropIfExists('stocks');
    }
};
