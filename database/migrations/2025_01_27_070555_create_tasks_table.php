<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("title", 100)->nullable(false);
            $table->text("description")->nullable();
            $table->dateTime("due_date")->nullable();
            $table->enum("status", ['draft', 'scheduled', 'published'])->default('draft');
            $table->unsignedBigInteger("user_id")->nullable(false);
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
