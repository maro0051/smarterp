<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->integer('number_of_positions')->default(1);
            $table->enum('status', ['open', 'in_progress', 'closed', 'on_hold'])->default('open');
            $table->date('deadline');
            $table->text('requirements')->nullable();
            $table->decimal('salary_range_min', 10, 2)->nullable();
            $table->decimal('salary_range_max', 10, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};