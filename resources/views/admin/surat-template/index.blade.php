@extends('layouts.admin')
@section('title', 'Manajemen Template Surat')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Card Form Upload -->
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default p-6 h-fit">
        <h5 class="font-bold text-heading text-lg mb-4 flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up text-brand"></i> Upload Template Baru
        </h5>
        <form action="{{ route('admin.surat-template.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="nama" class="block text-[13px] font-medium text-heading mb-1.5">Nama Template <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Contoh: Template Surat Pengantar Sumpah"
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[13px] py-2 px-3 focus:outline-none focus:border-brand transition-all @error('nama') border-red-500 @enderror">
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="file_template" class="block text-[13px] font-medium text-heading mb-1.5">File Template (Word) <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-border-default-medium rounded-base p-4 text-center hover:border-brand transition-colors relative cursor-pointer group bg-white/40">
                    <input type="file" name="file_template" id="file_template" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".docx,.doc">
                    <div class="space-y-1">
                        <i class="fa-solid fa-file-word text-3xl text-body-subtle group-hover:text-brand transition-colors"></i>
                        <div class="text-[13px] text-body group-hover:text-brand-strong transition-colors" id="file-name-placeholder">Pilih file atau seret ke sini</div>
                        <p class="text-[11px] text-body-subtle">Format yang didukung: .docx, .doc (Maks. 5MB)</p>
                    </div>
                </div>
                @error('file_template')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-base text-[13px] font-medium bg-brand text-white shadow-sm hover:bg-brand-strong transition-all">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Template
            </button>
        </form>

        <div class="mt-6 p-4 rounded-lg bg-neutral-primary-soft border border-border-default text-xs text-body leading-relaxed max-h-[300px] overflow-y-auto">
            <span class="font-bold text-brand uppercase tracking-wider block mb-2"><i class="fa-solid fa-info-circle mr-1"></i> Petunjuk Placeholders</span>
            Anda dapat menggunakan penanda berikut di dalam template Word Anda:
            <ul class="list-disc list-inside mt-2 space-y-1 text-body-subtle font-mono">
                <li>${nomor_permohonan} (Nomor Registrasi)</li>
                <li>${nomor_surat} / ${nomor_sk} (Nomor SK Advokat Pemohon)</li>
                <li>${nomor_balasan} (Format nomor surat balasan otomatis, contoh: [spasi] /PAN.W9-U/HM2.1.3/VII/2026)</li>
                <li>${tanggal_registrasi} (Tanggal Pemohon Mengajukan)</li>
                <li>${tanggal_pengajuan} / ${tanggal_surat} / ${tanggal_sk} (Tanggal SK Advokat Pemohon)</li>
                <li>${tanggal_hari_ini} / ${tanggal_cetak} / ${tanggal_surat_balasan} (Tanggal Surat Hari Ini)</li>
                <li>${bulan_romawi} (Bulan Berjalan Angka Romawi, contoh: VII)</li>
                <li>${tahun} / ${tahun_sekarang} (Tahun Berjalan, contoh: 2026)</li>
                <li>${nama_lengkap} / ${nama}</li>
                <li>${nik}</li>
                <li>${tempat_lahir}</li>
                <li>${tanggal_lahir}</li>
                <li>${jenis_kelamin}</li>
                <li>${alamat}</li>
                <li>${email}</li>
                <li>${no_hp}</li>
                <li>${organisasi}</li>
                <li>${nomor_sk}</li>
                <li>${tanggal_sk}</li>
                <li>${hari_tanggal} (Jadwal Sumpah)</li>
                <li>${hari} (Jadwal Sumpah)</li>
                <li>${tanggal_sumpah} (Jadwal Sumpah)</li>
                <li>${jam} / ${pukul} (Jadwal Sumpah)</li>
                <li>${tempat} / ${lokasi} (Jadwal Sumpah)</li>
                <li>${catatan} (Catatan Admin)</li>
            </ul>
        </div>
    </div>

    <!-- Tabel Daftar Template -->
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default lg:col-span-2 overflow-hidden h-fit">
        <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft flex justify-between items-center">
            <div>
                <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                    <i class="fa-solid fa-list text-brand"></i> Daftar Template Surat
                </h6>
                <p class="text-xs text-body-subtle mt-1">Kelola template aktif untuk pembuatan draf surat secara dinamis.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[5%]">No</th>
                        <th class="px-6 py-3 font-medium w-[45%]">Nama Template</th>
                        <th class="px-6 py-3 font-medium w-[20%] text-center">Status</th>
                        <th class="px-6 py-3 font-medium w-[30%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    @forelse($templates as $index => $item)
                    <tr class="hover:bg-neutral-secondary-soft transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-heading whitespace-normal">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-word text-blue-500 text-lg"></i>
                                <span>{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-default text-xs font-semibold bg-success-soft border border-border-success-subtle text-fg-success-strong">
                                    <i class="fa-solid fa-circle-check mr-1.5 text-xs"></i>Aktif
                                </span>
                            @else
                                <form action="{{ route('admin.surat-template.activate', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center px-2.5 py-1 rounded-default text-xs font-medium bg-neutral-primary-soft text-body shadow-sm hover:shadow-md hover:text-brand hover:border-brand border border-border-default transition-all">
                                        Aktifkan
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Download -->
                                <a href="{{ route('admin.surat-template.download', $item->id) }}" title="Unduh Template" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                    <i class="fa-solid fa-download text-xs"></i>
                                </a>
                                
                                <!-- Delete -->
                                <form action="{{ route('admin.surat-template.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @if($item->is_active) disabled title="Template aktif tidak dapat dihapus" @endif class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default {{ $item->is_active ? 'opacity-40 cursor-not-allowed border-none shadow-none hover:shadow-none' : '' }}">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-body-subtle italic">Belum ada template surat yang diunggah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('file_template').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Pilih file atau seret ke sini';
        document.getElementById('file-name-placeholder').textContent = fileName;
    });
</script>
@endpush
