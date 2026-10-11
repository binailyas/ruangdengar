<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Akun anonim yang dihapus dinonaktifkan (soft delete) supaya laporan dan chat tetap terhubung. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anonymous_accounts', fn (Blueprint $t) => $t->timestamp('deleted_at')->nullable());
    }

    public function down(): void
    {
        Schema::table('anonymous_accounts', fn (Blueprint $t) => $t->dropColumn('deleted_at'));
    }
};
