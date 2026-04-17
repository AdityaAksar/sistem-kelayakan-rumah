<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayah = [
            'Palu Barat' => ['Baru', 'Ujuna', 'Kamonji', 'Silae', 'Donggala Kodi', 'Kabonena', 'Tipo'],
            'Palu Timur' => ['Besusu Barat', 'Besusu Tengah', 'Besusu Timur', 'Lasoani', 'Poboya'],
            'Palu Selatan' => ['Birobuli Selatan', 'Birobuli Utara', 'Petobo', 'Pengawu', 'Tatura Selatan', 'Tatura Utara', 'Lolu Selatan', 'Lolu Utara'],
            'Palu Utara' => ['Mamboro', 'Mamboro Barat', 'Pantoloan', 'Pantoloan Boya', 'Baiya', 'Kayumalue Ngapa', 'Kayumalue Pajeko'],
            'Tatanga' => ['Tatura Selatan', 'Tatura Utara', 'Nunu', 'Boyaoge', 'Tavanjuka', 'Duyu', 'Palupi'],
            'Ulujadi' => ['Tipo', 'Donggala Kodi', 'Silae', 'Wombo', 'Watusampu', 'Buluri', 'Lambara'],
            'Mantikulore' => ['Talise', 'Lasoani', 'Kawatuna', 'Tondo', 'Poboya', 'Pantoloan', 'Layana Indah'],
            'Tawaeli' => ['Pantoloan', 'Pantoloan Boya', 'Baiya', 'Kayumalue Ngapa', 'Lambara', 'Mamboro', 'Mamboro Barat'],
        ];

        foreach ($wilayah as $kecamatan => $kelurahans) {
            $kec = Kecamatan::create(['nama_kecamatan' => $kecamatan]);
            foreach ($kelurahans as $kel) {
                Kelurahan::create([
                    'kecamatan_id' => $kec->id,
                    'nama_kelurahan' => $kel,
                ]);
            }
        }
    }
}
