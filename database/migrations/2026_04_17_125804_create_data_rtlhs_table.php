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
        Schema::create('data_rtlhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelurahan_id')->constrained('kelurahans')->cascadeOnDelete();
            $table->string('nama_kepala_rumah_tangga', 100);
            $table->string('nomor_kartu_keluarga', 100);
            $table->string('nik', 100);
            $table->text('alamat');
            $table->unsignedInteger('umur');
            $table->string('jenis_kelamin', 20);
            $table->string('pendidikan_terakhir', 100);
            $table->string('pekerjaan', 100);
            $table->unsignedBigInteger('penghasilan_per_bulan');
            $table->unsignedInteger('jumlah_keluarga_kk');
            $table->unsignedInteger('jumlah_penghuni');
            $table->string('kepemilikan_rumah', 100);
            $table->string('kepemilikan_tanah', 100);
            $table->boolean('aset_rumah_di_lokasi_lain');
            $table->boolean('aset_tanah_di_lokasi_lain');
            $table->string('jenis_kawasan', 100);
            $table->string('fungsi_ruang', 100);
            $table->unsignedInteger('luas_rumah');
            $table->unsignedInteger('luas_lahan');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('pondasi', 100);
            $table->string('kondisi_kolom', 100);
            $table->string('kondisi_rangka_atap', 100);
            $table->string('kondisi_plafon', 100);
            $table->string('kondisi_balok', 100);
            $table->string('kondisi_sloof', 100);
            $table->string('kondisi_jendela', 100);
            $table->string('kondisi_ventilasi', 100);
            $table->string('material_lantai_terluas', 100);
            $table->string('kondisi_lantai', 100);
            $table->string('material_dinding_terluas', 100);
            $table->string('kondisi_dinding', 100);
            $table->string('material_atap_terluas', 100);
            $table->string('kondisi_atap', 100);
            $table->string('sumber_penerangan', 100);
            $table->string('bantuan_pemerintah', 100)->nullable();
            $table->string('sumber_air_minum', 100);
            $table->string('jarak_sumber_air_tinja', 100);
            $table->string('kamar_mandi_jamban', 100);
            $table->string('jenis_jamban', 100);
            $table->string('jenis_tpa_tinja', 100);
            $table->string('nama_file_foto', 255)->nullable();
            $table->enum('status_validasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->date('tanggal_pendataan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rtlhs');
    }
};
