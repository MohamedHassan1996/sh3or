<?php

use App\Enums\Party\PriceListStatus;
use App\Enums\Party\PriceListType;
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
        Schema::create('party_price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->nullable()->constrained('parties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('pricelist_id')->nullable()->constrained('price_lists')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('status')->default(PriceListStatus::INACTIVE->value);
            $table->string('type')->default(PriceListType::SECONDARY->value);
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_price_lists');
    }
};
