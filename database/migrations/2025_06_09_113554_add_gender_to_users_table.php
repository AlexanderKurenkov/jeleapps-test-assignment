<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->after('password');
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_gender CHECK (gender IN ('male', 'female'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users DROP CONSTRAINT chk_gender");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};
