<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $user_table = config('attachment.user_table');
            $user_key_name = config('attachment.user_key_name');
            $user_column_type = config('attachment.user_key_type') == 'int' ? 'bigInteger' : 'string';
            $user_column = 'user_id';

            $table->id();
            $table->{$user_column_type}($user_column);
            $table->foreign($user_column)->references($user_key_name)->on($user_table);
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('path');
            $table->string('mime');
            $table->bigInteger('size');
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });

        Schema::create('attachables', function (Blueprint $table) {
            $table->foreignId('attachment_id')->constrained();
            $table->morphs('attachable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachables');
        Schema::dropIfExists('attachments');
    }
};
