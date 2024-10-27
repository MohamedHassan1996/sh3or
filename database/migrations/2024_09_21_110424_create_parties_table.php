<?php

use App\Enums\Party\partCancelStatus;
use App\Enums\Party\PartyCancelStatus;
use App\Enums\Party\PartyStatus;
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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('category_id')->nullable()->constrained('party_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('status')->default(PartyStatus::INACTIVE->value);
            $table->boolean('allow_cancel')->default(PartyCancelStatus::NON_CANCELLABLE->value);
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
