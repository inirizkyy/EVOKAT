<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
        * Seed the application's database.
        */
    public function run(): void
    {
        // Admin User
        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@eadvokat.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Organisasi Advokat
        \App\Models\OrganisasiAdvokat::create([
            'nama_organisasi' => 'PERADI',
            'alamat' => 'Jl. Advokat No. 1, Jakarta',
            'email' => 'info@peradi.or.id',
            'no_telp' => '021-12345678',
        ]);

        // Master Persyaratan
        $persyaratan = [
            ['nama_persyaratan' => 'KTP', 'deskripsi' => 'Kartu Tanda Penduduk Provinsi Lampung', 'is_required' => true],
            ['nama_persyaratan' => 'Ijazah S1 Hukum', 'deskripsi' => 'Legalisir ijazah S1 Hukum', 'is_required' => true],
            ['nama_persyaratan' => 'Sertifikat PKPA', 'deskripsi' => 'Sertifikat Pendidikan Khusus Profesi Advokat', 'is_required' => true],
            ['nama_persyaratan' => 'Sertifikat UPA', 'deskripsi' => 'Sertifikat Ujian Profesi Advokat', 'is_required' => true],
            ['nama_persyaratan' => 'Surat Magang', 'deskripsi' => 'Surat keterangan magang minimal 2 tahun', 'is_required' => true],
            ['nama_persyaratan' => 'SK Pengangkatan Advokat', 'deskripsi' => 'SK pengangkatan dari organisasi advokat', 'is_required' => true],
            ['nama_persyaratan' => 'Fotokopi Kartu Anggota Organisasi', 'deskripsi' => 'Fotokopi kartu anggota organisasi advokat', 'is_required' => true],
            ['nama_persyaratan' => 'Surat Pernyataan Tidak Berstatus PNS, TNI, Polri, Pejabat Negara', 'deskripsi' => 'Surat Keterangan tidak pernah dipidana dari Pengadilan Negeri karena melakukan tindak pidana kejahatan yang diancam dengan pidana penjara 5 tahun atau lebih', 'is_required' => true],
            ['nama_persyaratan' => 'Surat Keterangan Tidak Pernah Dipidana', 'deskripsi' => 'Surat Keterangan tidak pernah dipidana dari Pengadilan Negeri karena melakukan tindak pidana kejahatan yang diancam dengan pidana penjara 5 tahun atau lebih', 'is_required' => true],
            ['nama_persyaratan' => 'Foto Copy SK Kepengurusan Organisasi', 'deskripsi' => 'Foto Copy SK nama-nama Pengurus Organisasi Advokat (Pusat/Daerah)', 'is_required' => true],
        ];
        foreach ($persyaratan as $p) {
            \App\Models\MasterPersyaratan::create($p);
        }

        // Pengaturan Website
        \App\Models\PengaturanWebsite::create([
            'nama_instansi' => 'Pengadilan Tinggi Tanjungkarang',
            'alamat' => 'Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung',
            'email' => 'admin@pt-tanjungkarang.go.id',
            'telepon' => '(0721) 482431',
            'maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.970258169134!2d105.25902517565313!3d-5.421508294558231!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da42c8d23461%3A0x9599da5ea9079133!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1689874839818!5m2!1sen!2sid" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        ]);
        
        // FAQ
        \App\Models\Faq::create([
            'pertanyaan' => 'Bagaimana cara mengajukan permohonan sumpah advokat?',
            'jawaban' => 'Silakan klik tombol Ajukan Permohonan pada halaman utama, lalu isi formulir dan unggah dokumen persyaratan yang diminta.',
        ]);
    }
}
