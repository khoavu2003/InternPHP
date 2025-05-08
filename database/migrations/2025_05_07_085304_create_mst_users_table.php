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
        Schema::create('mst_users', function (Blueprint $table) {
            $table->bigIncrements('id'); // Khóa chính tự tăng
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->boolean('verify_email')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_delete')->default(false);
            $table->string('group_role')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_users');
    }
};
