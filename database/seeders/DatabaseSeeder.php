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
        \App\Models\Organization::create([
            'nama_organisasi' => 'Persatuan Advokat Indonesia',
            'singkatan' => 'PERADI',
            'status' => 'Aktif',
        ]);
        \App\Models\Organization::create([
            'nama_organisasi' => 'Kongres Advokat Indonesia',
            'singkatan' => 'KAI',
            'status' => 'Aktif',
        ]);
        \App\Models\Organization::create([
            'nama_organisasi' => 'Asosiasi Advokat Indonesia',
            'singkatan' => 'AAI',
            'status' => 'Aktif',
        ]);
        \App\Models\Organization::create([
            'nama_organisasi' => 'Ikatan Advokat Indonesia',
            'singkatan' => 'IKADIN',
            'status' => 'Aktif',
        ]);
        \App\Models\Organization::create([
            'nama_organisasi' => 'Himpunan Konsultan Hukum Pasar Modal',
            'singkatan' => 'HKHPM',
            'status' => 'Aktif',
        ]);
        \App\Models\Organization::create([
            'nama_organisasi' => 'Organisasi Usulan Baru',
            'singkatan' => 'OUB',
            'status' => 'Menunggu Persetujuan',
        ]);

        // Master Persyaratan
        $persyaratan = [
            ['nama_persyaratan' => 'Surat Permohonan dari Pimpinan Organisasi', 'deskripsi' => 'Surat permohonan resmi yang ditandatangani oleh pimpinan organisasi advokat', 'is_required' => true],
            ['nama_persyaratan' => 'Fotokopi SK Pendirian Organisasi & SK Kepengurusan', 'deskripsi' => 'Fotokopi SK Pendirian organisasi dari Kemenkum HAM dan SK Kepengurusan', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Fotokopi Surat Kartu Tanda Penduduk (KTP)', 'deskripsi' => 'Asli dan Fotokopi Surat Kartu Tanda Penduduk (KTP Lampung)', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Legalisir Ijazah Sarjana Hukum', 'deskripsi' => 'Asli dan Legalisir Ijazah Sarjana Hukum sudah 2 tahun kelulusan', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Legalisir Sertifikat PKPA', 'deskripsi' => 'Asli dan Legalisir Sertifikat Pendidikan Khusus Profesi Advokat (PKPA)', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Legalisir Sertifikat UPA', 'deskripsi' => 'Asli dan Legalisir Sertifikat Ujian Pendidikan Advokat (UPA)', 'is_required' => true],
            ['nama_persyaratan' => 'Surat Keterangan Magang', 'deskripsi' => 'Surat Keterangan Magang selama 2 tahun berturut-turut', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Legalisir SK Pengangkatan Advokat', 'deskripsi' => 'Asli dan Legalisir Surat Keputusan Pengangkatan Advokat (Nomor, Tanggal, dan Tempat)', 'is_required' => true],
            ['nama_persyaratan' => 'Fotokopi Kartu Anggota Organisasi (Opsional)', 'deskripsi' => 'Fotokopi Kartu Anggota Organisasi beserta Nomor Anggota (opsional)', 'is_required' => false],
            ['nama_persyaratan' => 'Asli Surat Pernyataan Tidak sebagai PNS/TNI/POLRI/Pejabat Negara', 'deskripsi' => 'Asli Surat Pernyataan Tidak sebagai PNS/TNI/POLRI/Pejabat Negara di atas materai Rp10.000', 'is_required' => true],
            ['nama_persyaratan' => 'Asli Surat Keterangan Tidak Pernah Dipidana', 'deskripsi' => 'Asli Surat Keterangan Tidak Pernah Dipidana dari Kepolisian dan Pengadilan', 'is_required' => true],
            ['nama_persyaratan' => 'Asli dan Fotokopi Akta Kelahiran', 'deskripsi' => 'Asli dan Fotokopi Akta Kelahiran', 'is_required' => true],
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
