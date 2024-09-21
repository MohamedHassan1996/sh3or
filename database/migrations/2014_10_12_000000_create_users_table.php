<?php

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->tinyInteger('role')->default(UserRole::CUSTOMER->value);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(UserStatus::INACTIVE->value);
            $table->timestamp('email_verified_at')->nullable();
            $this->CreatedUpdatedByRelationship($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
