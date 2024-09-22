<?php

use App\Enums\Party\Reservation\PayType;
use App\Enums\Party\Reservation\ReservationStatus;
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
        Schema::create('party_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->date('date');
            $table->time('start_prep');
            $table->time('end_prep');
            $table->decimal('price', 10, 2);
            $table->decimal('price_after_discount', 10, 2);
            $table->tinyInteger('status')->default(ReservationStatus::RESERVED->value);
            $table->tinyInteger('pay_type')->default(PayType::CARD->value);
            $table->foreignId('party_id')->nullable()->constrained('parties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_reservations');
    }
};
