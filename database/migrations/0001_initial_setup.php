<?php

use core\Database\Schema;

return new class {
    public function up(): void
    {
        Schema::table('sessions', function($table) {
            $table->string('id')->notNull()->primary();
            $table->foreignId('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity');

            $table->raw('INDEX (`user_id`)');
            $table->raw('INDEX (`last_activity`)');
        });
    }

    public function down(): void
    {
        Schema::drop('sessions');
    }
};