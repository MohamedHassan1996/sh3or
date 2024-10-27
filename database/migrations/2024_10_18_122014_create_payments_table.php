<?php

use App\Traits\CreatedUpdatedByMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use CreatedUpdatedByMigration;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->string('payment_number');
            $table->string('payment_guid');
            $table->string('cur');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->boolean('status')->default(0);
            $table->foreignId('reservation_id')->nullable()->constrained('party_reservations')->onUpdate('cascade')->onDelete('cascade');
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
