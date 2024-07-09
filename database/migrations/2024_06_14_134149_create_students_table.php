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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('address');
            $table->string('telephone');
            $table->unsignedBigInteger('filiere_id');
            $table->unsignedBigInteger('niveau_id');
            $table->unsignedBigInteger('section_id');
            $table->string('email')->unique();
            $table->string('studentId')->unique();
            $table->date('join_date');
            $table->year('join_year');
            $table->year('current_year');
            $table->date('birth_date');
            $table->decimal('total_amount')->default(350000);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->unsignedBigInteger('billmethod_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            $table->foreign('filiere_id')->references('id')->on('filieres')->onDelete('cascade');
            $table->foreign('niveau_id')->references('id')->on('niveaux')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('billmethod_id')->references('id')->on('bill_methods')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
