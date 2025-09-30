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
        Schema::table('users', function (Blueprint $table) { // <-- replace 'users' ជា table ពិត
            $table->bigInteger('phonenumber')->nullable()->after('email');
            $table->string('address')->nullable()->after('phonenumber');
            $table->string('profile_picture')->nullable()->after('address');
            $table->string("status")->default("active")->after('profile_picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phonenumber', 'address', 'profile_picture', 'status']);
        });
    }
};
