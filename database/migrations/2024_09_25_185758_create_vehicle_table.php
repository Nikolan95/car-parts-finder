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
        Schema::create('vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('vin');
            $table->string('brand');
            $table->string('model');
            $table->string('date');
            $table->string('manufactured');
            $table->string('prodrange');
            $table->string('market');
            $table->string('engine');
            $table->string('engine_nr');
            $table->string('engine_info');
            $table->string('transmission');
            $table->string('framecolor');
            $table->string('trimcolor');
            $table->string('filter_level');
            $table->text('catalogInfoToken');
            $table->text('catalogShortToken');
            $table->text('token');
            $table->text('partApplicabilityToken');
            $table->text('navigationTreeToken');
            $table->text('groupsToken');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle');
    }
};
