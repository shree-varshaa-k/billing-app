<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Updated new requirements
            $table->string('name');                     // Supplier Name
            $table->string('company_name')->nullable(); // Organization Name
            $table->string('address')->nullable();      // Address
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gst_number')->nullable();

            // NEW FIELD: Payment Type
            $table->string('payment_type')->nullable(); // Cash, UPI, Card etc.

            // Removed fields
            $table->string('payment_terms')->nullable();   // REMOVED
            $table->decimal('opening_balance', 15, 2)->default(0.00);;     // REMOVED

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vendors');
    }
};
