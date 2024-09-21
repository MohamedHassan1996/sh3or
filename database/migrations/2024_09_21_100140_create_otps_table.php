<?php

use App\Enums\Otp\OtpType;
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

        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('otp');
            $table->string('phone');
            $table->timestamp('expire_at')->nullable();
            $table->boolean('type')->default(OtpType::REGISTER->value);
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
