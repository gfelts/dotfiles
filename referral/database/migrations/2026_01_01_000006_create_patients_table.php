<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('chart_number', 50)->nullable()->unique();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->date('dob');
            $table->string('parent_name', 150)->nullable();
            $table->string('phone', 20);
            $table->string('best_time', 100)->nullable();
            $table->text('insurance')->nullable();
            $table->text('special_considerations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
