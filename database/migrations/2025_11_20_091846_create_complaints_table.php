<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::create('complaints', function (Blueprint $table) {
        $table->id(); // idklacht
        $table->text('description')->nullable();
        $table->string('location_text')->nullable();
        $table->enum('urgency', ['low','medium','high'])->default('low');
        $table->boolean('is_resolved')->default(false);
        $table->foreignId('reporter_id')->constrained('reporters')->onDelete('cascade');
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        $table->string('image_path')->nullable();
        $table->text('admin_note')->nullable();
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
