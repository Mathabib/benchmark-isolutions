<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->string('nama_task');
        $table->string('status')->default('todo'); // contoh: todo, inprogress, done
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('estimate')->nullable();
        $table->foreignId('assign_to')->nullable()->constrained('users')->onDelete('set null');
        $table->string('priority')->default('medium'); // contoh: low, medium, high
        $table->string('description')->nullable()->default('...'); // contoh: low, medium, high
        $table->integer('progress')->nullable()->default(0);
        $table->text('comment')->nullable(); // comment awal
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
