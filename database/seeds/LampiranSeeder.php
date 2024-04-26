<?php

use App\Models\Surat\Lampiran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LampiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lampiran')->truncate();

        Lampiran::insert([
            [
                'jenis_surat_id' => 4,
                'nama_lampiran' => 'Pas Foto 2 x 3 berwarna sebanyak 2 lembar',
                'keterangan' => 'Persyaratan Pelayanan: (Jika tidak tetap). Format Dijadikan 1 dalam bentuk PDF',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 1,
                'nama_lampiran' => 'Surat Pindah dari alamat asal bagi pendatang',
                'keterangan' => 'Persyaratan Pelayanan: Surat Pindah dari alamat asal bagi pendatang',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 1,
                'nama_lampiran' => 'Surat Nikah',
                'keterangan' => 'Persyaratan Pelayanan: Surat Nikah',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 1,
                'nama_lampiran' => 'FC Akta Kelahiran',
                'keterangan' => 'Persyaratan Pelayanan: FC Akta Kelahiran',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 1,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga (KK)',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 15,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 15,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 12,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 12,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 13,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 13,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 7,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 7,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 7,
                'nama_lampiran' => 'Data Calon Istri/Suami termasuk orang tua (Bapak /Ibu)',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 7,
                'nama_lampiran' => 'Surat Perceraian dan Putusan Pengadilan Agama/Pengadilan Negeri.',
                'keterangan' => 'Persyaratan Pelayanan: Surat Perceraian dan Putusan Pengadilan Agama/Pengadilan Negeri.',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 18,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 18,
                'nama_lampiran' => 'Lunas PBB',
                'keterangan' => 'Persyaratan Pelayanan: Lunas PBB',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 18,
                'nama_lampiran' => 'Fotokopi Sertifikat',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Sertifikat',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 17,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KK',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 17,
                'nama_lampiran' => 'KTP asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 17,
                'nama_lampiran' => 'Surat Keterangan Kematian Dari Dokter/RS',
                'keterangan' => 'Persyaratan Pelayanan: Surat Keterangan Kematian Dari Dokter/RS',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 17,
                'nama_lampiran' => 'Surat Pernyataan dari Ahli Waris/Kerabat',
                'keterangan' => 'Persyaratan Pelayanan: Surat Pernyataan dari Ahli Waris/Kerabat',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 16,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 16,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 2,
                'nama_lampiran' => 'Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 2,
                'nama_lampiran' => 'Surat Pindah dari alamat asal bagi pendatang',
                'keterangan' => 'Persyaratan Pelayanan: Surat Pindah dari alamat asal bagi pendatang',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 2,
                'nama_lampiran' => 'Fotokopi Surat Nikah',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Surat Nikah',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 2,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 2,
                'nama_lampiran' => 'Surat Keterangan Kehilangan dari Kepolisian',
                'keterangan' => 'Jika ingin membuat baru karena rusak / hilang',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 1,
                'nama_lampiran' => 'Surat Keterangan Kehilangan dari Kepolisian',
                'keterangan' => 'Jika ingin membuat baru karena hilang',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 3,
                'nama_lampiran' => 'KTP asli yang masih berlaku',
                'keterangan' => 'Persyaratan Pelayanan: KTP asli',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 3,
                'nama_lampiran' => 'FC KTP',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KTP',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 3,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 8,
                'nama_lampiran' => 'FC Kartu Keluarga',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Kartu Keluarga',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 8,
                'nama_lampiran' => 'FC KTP Suami dan Istri',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 8,
                'nama_lampiran' => 'Surat Nikah Asli atau Keterangan Nikah',
                'keterangan' => 'Persyaratan Pelayanan: Surat Nikah Asli atau Keterangan Nikah',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 8,
                'nama_lampiran' => 'Fotokopi Surat Nikah atau Keterangan Nikah',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Surat Nikah atau Keterangan Nikah',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 8,
                'nama_lampiran' => 'Fotokopi Surat Keterangan Kelahiran dari Bidan/RS',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi Surat Keterangan Kelahiran dari Bidan/RS',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'Validasi dari Disdukcapil',
                'keterangan' => 'Persyaratan Pelayanan: Validasi dari Disdukcapil',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'Surat Keterangan Pindah WNI',
                'keterangan' => 'Persyaratan Pelayanan: Surat Keterangan Pindah WNI',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'Surat Keterangan Datang WNI',
                'keterangan' => 'Persyaratan Pelayanan: Surat Keterangan Datang WNI',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: DIjadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 5,
                'nama_lampiran' => 'SKCK',
                'keterangan' => 'Persyaratan Pelayanan: SKCK (bagi yang pindah antar Kabupaten dan Propinsi)',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 4,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 4,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 4,
                'nama_lampiran' => 'Fotokopi KK yang ditumpangi',
                'keterangan' => 'Persyaratan Pelayanan: Fotokopi KK yang ditumpangi (Jika tidak tetap)',
                'kategori' => false,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 11,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 11,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 20,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 20,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 10,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 10,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 6,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 6,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 14,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 14,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 9,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 9,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 21,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 21,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 19,
                'nama_lampiran' => 'KTP Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
            [
                'jenis_surat_id' => 19,
                'nama_lampiran' => 'KK Asli dan Fotokopi',
                'keterangan' => 'Persyaratan Pelayanan: Dijadikan 1 dalam bentuk PDF',
                'kategori' => true,
                'status' => true,
                'created_at' => \Carbon\Carbon::now('Asia/jakarta'),
                'updated_at' => \Carbon\Carbon::now('Asia/jakarta')
            ],
        ]);
    }
}
