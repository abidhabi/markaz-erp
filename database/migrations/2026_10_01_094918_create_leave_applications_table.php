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
        Schema::create('leave_applications', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->string('leave_type');
           $table->date('from_date');
           $table->date('to_date');
           $table->decimal('duration', 4, 1);
           $table->text('reason');
           $table->string('attachment')->nullable();
           $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
           $table->timestamps();
   });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
