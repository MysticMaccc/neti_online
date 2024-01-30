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
        //
        Schema::table('email_configs', function (Blueprint $table) {
            $table->string('MAIL_MAILER')->default(NULL)->change();
            $table->string('MAIL_HOST')->default(NULL)->change();
            $table->string('MAIL_PORT')->default(NULL)->change();
            $table->string('MAIL_USERNAME')->default(NULL)->change();
            $table->string('MAIL_PASSWORD')->default(NULL)->change();
            $table->string('MAIL_ENCRYPTION')->default(NULL)->change();
            $table->string('MAIL_FROM_ADDRESS')->default(NULL)->change();
            $table->string('MAIL_FROM_NAME')->default(NULL)->change();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
