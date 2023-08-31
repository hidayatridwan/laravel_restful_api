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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable(false)->after('id');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->string('email', 100)->nullable()->after('last_name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->unsignedBigInteger('user_id')->nullable(false)->after('phone');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email');
            $table->dropColumn('phone');
        });
    }
};
