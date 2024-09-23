<?php

use App\Enums\Facility\FStatus;
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
        Schema::create('party_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->nullable()->constrained('parties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('facility_id')->nullable()->constrained('facilities')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('status')->default(FStatus::ACTIVE->value);
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_facilities');
    }
};
