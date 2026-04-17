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
        Schema::create('hasil_prediksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_rtlh_id')->constrained('data_rtlhs')->cascadeOnDelete();
            $table->foreignId('model_version_id')->nullable()->constrained('model_versions')->nullOnDelete();
            $table->enum('label_prediksi', ['rlh', 'rtlh']);
            $table->decimal('confidence_score', 5, 4);
            $table->timestamp('predicted_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_prediksis');
    }
};
