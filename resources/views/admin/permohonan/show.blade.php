@extends('layouts.admin')
@section('title', 'Detail Permohonan')

@section('actions')
<a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Data Pemohon -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col h-full">
        <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
            <h6 class="m-0 font-bold text-heading">Data Pemohon</h6>
        </div>
        <div class="p-6 flex-1">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">NIK</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->nik ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Nama Lengkap</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Tempat, Tanggal Lahir</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->tempat_lahir ?? '-' }}, {{ $permohonan->pemohon->tanggal_lahir ? \Carbon\Carbon::parse($permohonan->pemohon->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Jenis Kelamin</span>
                    <span class="text-heading sm:w-2/3">{{ ($permohonan->pemohon->jenis_kelamin ?? '') == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Alamat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->alamat ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Email</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->email ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">No. HP</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->no_hp ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Organisasi Advokat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Nomor SK Advokat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->nomor_sk ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Tanggal SK Advokat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->tanggal_sk ? \Carbon\Carbon::parse($permohonan->pemohon->tanggal_sk)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Status Permohonan</span>
                    <span class="sm:w-2/3">
                        @if($permohonan->status == 'Menunggu Verifikasi')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Menunggu</span>
                        @elseif($permohonan->status == 'Disetujui')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">Disetujui</span>
                        @elseif($permohonan->status == 'Selesai')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-brand-softer border border-border-brand-subtle text-fg-brand-strong">Selesai</span>
                        @elseif($permohonan->status == 'Ditolak')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Ditolak</span>
                        @elseif($permohonan->status == 'Verifikasi Berkas Fisik')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-folder-open mr-1"></i>Verifikasi Berkas Fisik</span>
                        @elseif($permohonan->status == 'Diproses')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Diproses</span>
                        @elseif($permohonan->status == 'Dijadwalkan Sumpah')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-regular fa-calendar-check mr-1"></i>Dijadwalkan Sumpah</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning">{{ $permohonan->status }}</span>
                        @endif
                    </span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Tanggal Pengajuan</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Catatan</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->catatan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="flex flex-col space-y-8">
        <!-- Dokumen Persyaratan -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Dokumen Persyaratan</h6>
            </div>
            <div class="p-0 flex-1">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                        <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nama Persyaratan</th>
                                <th class="px-6 py-3 font-medium">File</th>
                                <th class="px-6 py-3 font-medium">Status Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-neutral-primary divide-y divide-border-default">
                            @forelse($permohonan->dokumenPersyaratan as $dok)
                                <tr class="hover:bg-neutral-secondary-soft transition-colors">
                                    <td class="px-6 py-4 whitespace-normal">{{ $dok->masterPersyaratan->nama_persyaratan ?? 'Persyaratan' }}</td>
                                    <td class="px-6 py-4">
                                        @if($dok->file_path)
                                            <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-base text-sm font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                                                <i class="fas fa-file-alt mr-2"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-fg-danger">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($dok->status_dokumen == 'Valid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">Valid</span>
                                        @elseif($dok->status_dokumen == 'Tidak Valid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Tidak Valid</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-body-subtle">Belum ada dokumen yang diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Aksi Verifikasi -->
        @if($permohonan->status == 'Menunggu Verifikasi')
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col"
             x-data="{
                selectedStatus: '',
                hariVerifikasi: '',
                setHariVerifikasi(val) {
                    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    this.hariVerifikasi = val ? days[new Date(val).getDay()] : '';
                }
             }">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Aksi Verifikasi</h6>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[14px] font-medium text-heading mb-2">Status Verifikasi</label>
                        <select name="status" x-model="selectedStatus" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Verifikasi Berkas Fisik">Verifikasi Berkas Fisik (Jadwalkan Pengecekan Fisik)</option>
                            <option value="Ditolak">Ditolak (Ada Kekurangan/Tidak Valid)</option>
                        </select>
                    </div>



                    <!-- Jadwal Verifikasi Fisik (tampil jika dipilih) -->
                    <div x-show="selectedStatus === 'Verifikasi Berkas Fisik'" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-4 p-4 rounded-xl bg-warning-soft border border-border-warning-subtle space-y-4">
                        <p class="text-[13px] font-semibold text-fg-warning flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            Tentukan jadwal pemohon datang untuk pengecekan berkas fisik
                        </p>
                        <div>
                            <label class="block text-[13px] font-medium text-heading mb-1">Tanggal <span class="text-fg-danger">*</span></label>
                            <input type="date" name="tanggal_verifikasi_fisik" min="{{ now()->toDateString() }}"
                                   @change="setHariVerifikasi($event.target.value)"
                                   class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                            <!-- Hari otomatis -->
                            <div x-show="hariVerifikasi" class="mt-2 flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-fg-warning text-sm"></i>
                                <span class="text-[13px] font-semibold text-fg-warning">Hari: <span x-text="hariVerifikasi"></span></span>
                            </div>
                            <!-- Hidden input hari dikirim ke server -->
                            <input type="hidden" name="hari_verifikasi_fisik" :value="hariVerifikasi">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan</label>
                        <textarea name="catatan" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:text-brand-strong">
                        <i class="fas fa-save mr-2"></i> Simpan Verifikasi
                    </button>
                </form>
            </div>
        </div>

        @elseif($permohonan->status == 'Verifikasi Berkas Fisik')
        <!-- Panel info jadwal verifikasi fisik -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-warning-subtle flex flex-col">
            <div class="py-4 px-6 border-b border-border-warning-subtle bg-warning-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-warning flex items-center gap-2"><i class="fa-solid fa-folder-open"></i> Verifikasi Berkas Fisik</h6>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 p-4 rounded-xl bg-white border border-border-default shadow-sm">
                        <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Hari</p>
                        <p class="text-[17px] font-bold text-heading">{{ $permohonan->hari_verifikasi_fisik ?? '-' }}</p>
                    </div>
                    <div class="flex-1 p-4 rounded-xl bg-white border border-border-default shadow-sm">
                        <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Tanggal</p>
                        <p class="text-[17px] font-bold text-heading">
                            {{ $permohonan->tanggal_verifikasi_fisik ? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>
                </div>
                <p class="text-[13px] text-body leading-relaxed">Pemohon di jadwalkan hadir untuk pengecekan berkas fisik pada tanggal di atas. Setelah pengecekan selesai, lanjutkan proses verifikasi di bawah.</p>

                <!-- Form lanjut verifikasi setelah fisik -->
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" class="pt-2 border-t border-border-default"
                      x-data="{ selectedStatus: '' }">
                    @csrf
                    <p class="text-[13px] font-semibold text-heading mb-3">Hasil Pengecekan Berkas Fisik:</p>
                    <div class="mb-3">
                        <select name="status" x-model="selectedStatus" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Diproses">Diproses (Generate Draft Surat &amp; Jadwalkan Sumpah)</option>
                            <option value="Ditolak">Ditolak (Berkas Fisik Tidak Sesuai)</option>
                        </select>
                    </div>

                    <!-- Input Jadwal Sumpah (tampil jika Diproses) -->
                    <div x-show="selectedStatus === 'Diproses'" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mb-4 p-4 rounded-xl bg-brand/5 border border-border-brand-subtle space-y-4">
                        <p class="text-[13px] font-semibold text-brand flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            Tentukan Jadwal Pelaksanaan Sumpah Advokat
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[13px] font-medium text-heading mb-1">Tanggal Sumpah <span class="text-fg-danger">*</span></label>
                                <input type="date" name="tanggal_sumpah" :required="selectedStatus === 'Diproses'"
                                       class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                            </div>
                            <div>
                                <label class="block text-[13px] font-medium text-heading mb-1">Jam Sumpah <span class="text-fg-danger">*</span></label>
                                <input type="time" name="jam_sumpah" :required="selectedStatus === 'Diproses'"
                                       class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-heading mb-1">Lokasi Sumpah <span class="text-fg-danger">*</span></label>
                            <input type="text" name="lokasi_sumpah" value="Ruang Sidang Utama Pengadilan Tinggi Tanjungkarang" :required="selectedStatus === 'Diproses'"
                                   class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan / Keterangan Tambahan</label>
                        <textarea name="catatan" rows="2" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" placeholder="Catatan hasil pengecekan fisik..."></textarea>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:text-brand-strong">
                        <i class="fas fa-check-circle mr-2"></i> Lanjutkan Proses Surat
                    </button>
                </form>
            </div>
        </div>

        @elseif($permohonan->status == 'Diproses')
        <!-- Panel info status Diproses dan form ke Disetujui/Ditolak -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col"
             x-data="{
                selectedStatus: '',
             }">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Proses Draf Surat Pengantar</h6>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-[13px] text-body leading-relaxed">Draf Surat Pengantar Sumpah telah otomatis dibuat oleh sistem. Silakan unduh draf surat tersebut di bawah ini untuk dicetak dan ditandatangani basah oleh Ketua Pengadilan Tinggi / Pejabat yang berwenang.</p>

                @php
                    $hasActiveTemplate = \App\Models\SuratTemplate::where('is_active', true)->exists();
                    $isDocx = $permohonan->file_surat ? Str::endsWith($permohonan->file_surat, ['.docx', '.doc']) : $hasActiveTemplate;
                @endphp
                <form action="{{ route('admin.permohonan.download-surat', $permohonan->id) }}" method="GET" class="mb-6 space-y-4 max-w-md">
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-2">Jabatan Penandatangan <span class="text-fg-danger">*</span></label>
                        <select name="jabatan" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">Pilih Jabatan Penandatangan</option>
                            <option value="PANITERA" {{ old('jabatan', request('jabatan')) === 'PANITERA' ? 'selected' : '' }}>PANITERA</option>
                            <option value="PLH. PANITERA" {{ old('jabatan', request('jabatan')) === 'PLH. PANITERA' ? 'selected' : '' }}>PLH. PANITERA</option>
                            <option value="PLT. PANITERA" {{ old('jabatan', request('jabatan')) === 'PLT. PANITERA' ? 'selected' : '' }}>PLT. PANITERA</option>
                        </select>
                        @error('jabatan')
                            <div class="text-xs text-fg-danger mt-1 font-bold">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:opacity-90">
                        @if($isDocx)
                            <i class="fa-solid fa-file-word mr-2"></i> Download Draf Surat Pengantar (Word)
                        @else
                            <i class="fa-solid fa-file-pdf mr-2"></i> Download Draf Surat Pengantar (PDF)
                        @endif
                    </button>
                </form>

                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-border-default space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Hasil Verifikasi &amp; Tanda Tangan Surat <span class="text-fg-danger">*</span></label>
                        <select name="status" x-model="selectedStatus" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Disetujui">Disetujui (Upload Surat Bertanda Tangan)</option>
                            <option value="Ditolak">Ditolak (Dibatalkan/Tidak Valid)</option>
                        </select>
                    </div>

                    <!-- Upload File Bertanda Tangan (tampil jika Disetujui) -->
                    <div x-show="selectedStatus === 'Disetujui'" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="p-4 rounded-xl bg-success-soft border border-border-success-subtle space-y-3">
                        <label class="block text-[13px] font-bold text-fg-success-strong">Unggah Surat Pengantar Final Bertanda Tangan (PDF) <span class="text-fg-danger">*</span></label>
                        <input type="file" name="surat_bertanda_tangan" accept=".pdf" :required="selectedStatus === 'Disetujui'"
                               class="block w-full text-sm text-heading file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all">
                        <p class="text-[12px] text-fg-success font-medium">Hanya menerima file PDF dengan ukuran maksimal 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" placeholder="Tambahkan catatan keputusan..."></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:text-brand-strong">
                        <i class="fas fa-save mr-2"></i> Simpan Keputusan Final
                    </button>
                </form>
            </div>
        </div>

        @if(in_array($permohonan->status, ['Disetujui', 'Dijadwalkan Sumpah', 'Selesai']) && $permohonan->file_surat)
        <!-- Panel Surat Pengantar Final -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-success-subtle flex flex-col">
            <div class="py-4 px-6 border-b border-border-success-subtle bg-success-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-success-strong flex items-center gap-2"><i class="fa-solid fa-file-circle-check"></i> Surat Pengantar Final</h6>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-[13px] text-body leading-relaxed">Surat pengantar final yang telah ditandatangani basah oleh Ketua Pengadilan Tinggi sudah diunggah dan aktif.</p>
                <a href="{{ route('admin.permohonan.download-surat', $permohonan->id) }}" class="inline-flex items-center px-4 py-2.5 rounded-base text-sm font-bold bg-neutral-primary-soft text-success shadow-sm hover:shadow-md hover:text-fg-success-strong active:shadow-inset transition-all border border-success">
                    <i class="fa-solid fa-download mr-2"></i> Download Surat Final (PDF)
                </a>
            </div>
        </div>
        @endif

        @elseif($permohonan->status == 'Dijadwalkan Sumpah')
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-success-strong"><i class="fa-solid fa-calendar-check mr-2"></i>Jadwal Sumpah Advokat</h6>
            </div>
            <div class="p-6 space-y-4">
                @if($permohonan->jadwalSumpah)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-white border border-border-default shadow-sm">
                        <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Hari</p>
                        <p class="text-[16px] font-bold text-heading">
                            {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->locale('id')->translatedFormat('l') }}
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-white border border-border-default shadow-sm">
                        <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Tanggal</p>
                        <p class="text-[16px] font-bold text-heading">
                            {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-white border border-border-default shadow-sm">
                        <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Jam</p>
                        <p class="text-[16px] font-bold text-heading">
                            {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->jam)->format('H:i') }} WIB
                        </p>
                    </div>
                </div>
                <div class="p-4 rounded-xl bg-white border border-border-default shadow-sm">
                    <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Lokasi</p>
                    <p class="text-[14px] font-medium text-heading">{{ $permohonan->jadwalSumpah->lokasi ?? '-' }}</p>
                </div>
                @if($permohonan->jadwalSumpah->keterangan)
                <div class="p-4 rounded-xl bg-white border border-border-default shadow-sm">
                    <p class="text-[12px] font-semibold uppercase tracking-wider text-body-subtle mb-1">Catatan</p>
                    <p class="text-[14px] text-heading">{{ $permohonan->jadwalSumpah->keterangan }}</p>
                </div>
                @endif
                <p class="text-[13px] text-body-subtle"><i class="fa-solid fa-envelope mr-1"></i>Surat pemberitahuan jadwal telah dikirimkan ke email pemohon.</p>
                @endif

                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" class="pt-4 border-t border-border-default" onsubmit="return confirm('Apakah Anda yakin menandai pemohon ini telah SELESAI disumpah?')">
                    @csrf
                    <input type="hidden" name="status" value="Selesai">
                    <input type="hidden" name="catatan" value="Telah melaksanakan Sumpah Advokat.">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-success shadow-sm hover:shadow-md active:shadow-inset transition-all border border-success hover:text-fg-success-strong">
                        <i class="fas fa-flag-checkered mr-2"></i> Tandai Selesai (Disumpah)
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
