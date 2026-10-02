<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type'); // casual, sick, medical, unpaid
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_half_day')->default(false);
            $table->string('half_day_period')->nullable(); // morning, afternoon
            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable(); // For medical certificates
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('leave_requests'); }
};