@extends('layouts.admin')
@section('title', 'Detail & Verifikasi Anggota')

@section('actions')
<a href="{{ route('admin.permohonan.show', $pemohon->permohonan_id) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Permohonan
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" x-data="verifikasiForm()">
    
    <!-- Kolom Kiri: Profil Anggota -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col h-full">
        <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
            <h6 class="m-0 font-bold text-heading">Profil Anggota Pemohon</h6>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex flex-col border-b border-border-default pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nama Lengkap &amp; Gelar</span>
                <span class="text-heading font-bold text-lg">{{ $pemohon->nama_lengkap }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default pb-3 font-mono">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">NIK (16 Digit)</span>
                <span class="text-heading font-semibold">{{ $pemohon->nik }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</span>
                <span class="text-heading font-semibold">{{ $pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Jenis Kelamin</span>
                <span class="text-heading font-semibold">{{ $pemohon->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Email Aktif Anggota</span>
                <span class="text-heading font-semibold">{{ $pemohon->email }}</span>
            </div>
            <div class="flex flex-col pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Organisasi Pengusung</span>
                <span class="text-heading font-bold">{{ $pemohon->permohonan->organisasi->nama_organisasi ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Dokumen Persyaratan & Keputusan Verifikasi -->
    <div class="lg:col-span-2 space-y-8 min-w-0">
        
        <!-- Form Verifikasi Dokumen dan Keputusan -->
        <form action="{{ route('admin.permohonan.verifikasi-member', $pemohon->id) }}" method="POST">
            @csrf
            
            <!-- Dokumen Persyaratan -->
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
                <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                    <h6 class="m-0 font-bold text-heading">Pemeriksaan Berkas Unggahan</h6>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                            <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                                <tr>
                                    <th class="px-6 py-3">Persyaratan Dokumen</th>
                                    <th class="px-6 py-3 text-center">Lihat File</th>
                                    <th class="px-6 py-3">Status Validasi Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white font-medium">
                                @foreach($pemohon->dokumenPersyaratan as $dok)
                                    <!-- Row Utama Dokumen -->
                                    <tr class="hover:bg-neutral-secondary-soft/50 transition-colors duration-150">
                                        <td class="px-6 pt-4 pb-2 whitespace-normal font-semibold text-heading border-t border-border-default">
                                            {{ $dok->masterPersyaratan->nama_persyaratan }}
                                            @if($dok->masterPersyaratan->is_required) <span class="text-fg-danger">*</span> @endif
                                        </td>
                                        <td class="px-6 pt-4 pb-2 text-center border-t border-border-default">
                                            @if($dok->file_path)
                                                <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-base text-xs font-bold bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md border border-brand transition-all">
                                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Buka File
                                                </a>
                                            @else
                                                <span class="text-fg-danger text-xs font-bold">Belum diunggah</span>
                                            @endif
                                        </td>
                                        <td class="px-6 pt-4 pb-2 border-t border-border-default">
                                            <div class="flex items-center gap-4">
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $dok->id }}]" value="Valid" {{ old("dokumen.{$dok->id}", $dok->status_dokumen) == 'Valid' ? 'checked' : '' }} required @change="updateStatus()" class="text-success focus:ring-success w-4 h-4">
                                                    <span class="text-xs text-heading font-bold">Valid</span>
                                                </label>
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $dok->id }}]" value="Tidak Valid" {{ old("dokumen.{$dok->id}", $dok->status_dokumen) == 'Tidak Valid' ? 'checked' : '' }} required @change="updateStatus()" class="text-danger focus:ring-danger w-4 h-4">
                                                    <span class="text-xs text-heading font-bold">Tidak Valid</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row Catatan / Alasan di Bawahnya -->
                                    <tr class="hover:bg-neutral-secondary-soft/50 transition-colors duration-150 border-b border-border-default">
                                        <td colspan="3" class="px-6 pb-4 pt-1">
                                            <div class="flex flex-col gap-1.5">
                                                <label class="text-[11px] font-bold text-body-subtle flex items-center gap-1">
                                                    <i class="fa-regular fa-comment-dots text-brand"></i>
                                                    <span>Catatan / Alasan:</span>
                                                </label>
                                                <input type="text" name="keterangan_dokumen[{{ $dok->id }}]" value="{{ old("keterangan_dokumen.{$dok->id}", $dok->keterangan) }}" placeholder="Tulis alasan jika berkas tidak valid atau butuh revisi..."
                                                       class="block w-full rounded border border-border-default bg-white text-xs py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all font-normal shadow-sm">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Keputusan Akhir Verifikasi Anggota -->
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-4">
                <h6 class="font-bold text-heading text-base border-b border-border-default pb-3"><i class="fa-solid fa-circle-exclamation text-brand mr-1.5"></i> Keputusan Akhir Verifikasi Anggota</h6>
                
                <div>
                    <label class="block text-[14px] font-bold text-heading mb-2">Status Persetujuan Anggota <span class="text-fg-danger">*</span></label>
                    <div class="relative">
                        <select x-model="statusVerifikasi" class="block w-full rounded-base border border-border-default-medium bg-neutral-secondary-soft text-[14px] text-heading py-2.5 px-3 focus:outline-none transition-all pointer-events-none opacity-80" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Disetujui">Setujui Anggota (Berkas Valid &amp; Lengkap)</option>
                            <option value="Ditolak">Tolak Anggota (Berkas Bermasalah / Butuh Koreksi)</option>
                        </select>
                        <input type="hidden" name="status_verifikasi" :value="statusVerifikasi">
                    </div>
                </div>

                <p class="text-[12px] text-fg-danger-strong font-medium"><i class="fa-solid fa-circle-info mr-1"></i> Log keputusan/penolakan ini akan dikirimkan otomatis melalui Email Queue ke Pimpinan Organisasi &amp; Anggota yang bersangkutan beserta daftar dokumen tidak valid.</p>

                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 rounded-base text-[16px] font-bold bg-brand text-white shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:opacity-95">
                    <i class="fa-solid fa-circle-check mr-2"></i> Simpan Keputusan Verifikasi Anggota
                </button>
            </div>
            
        </form>

    </div>
</div>

<script>
function verifikasiForm() {
    return {
        statusVerifikasi: '{{ old('status_verifikasi', $pemohon->status_verifikasi ?? '') }}',
        
        init() {
            this.updateStatus();
        },
        
        updateStatus() {
            const radios = document.querySelectorAll('input[type="radio"][name^="dokumen["]');
            if (radios.length === 0) return;
            
            const groups = {};
            radios.forEach(r => {
                if (!groups[r.name]) groups[r.name] = [];
                groups[r.name].push(r);
            });
            
            let hasInvalid = false;
            let allChecked = true;
            
            for (const name in groups) {
                const group = groups[name];
                const checkedRadio = group.find(r => r.checked);
                if (!checkedRadio) {
                    allChecked = false;
                } else if (checkedRadio.value === 'Tidak Valid') {
                    hasInvalid = true;
                }
            }
            
            if (hasInvalid) {
                this.statusVerifikasi = 'Ditolak';
            } else if (allChecked) {
                this.statusVerifikasi = 'Disetujui';
            }
        }
    }
}
</script>
@endsection
