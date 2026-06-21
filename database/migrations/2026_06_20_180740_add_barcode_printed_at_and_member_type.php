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
        Schema::table('books', function (Blueprint $table) {
            $table->timestamp('barcode_printed_at')->nullable()->after('barcode_id');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->string('member_type')->default('student')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('barcode_printed_at');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('member_type');
        });
    }
};
