@extends('layouts.admin')
@section('title', 'Detail Permohonan')

@section('actions')
<a href="{{ route($role . '.permohonan.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div x-data="{ 
    actionUrl: '', 
    decision: '', 
    notes: '', 
    activeMemberIndex: 0,
    
    updateDecisionFromDocs() {
        const hasInvalid = Array.from(document.querySelectorAll('input[type=radio][value=\'Tidak Valid\'][name^=\'dokumen[\']')).some(r => r.checked);
        
        if (hasInvalid) {
            this.decision = 'tidak_valid';
            this.actionUrl = '{{ route($role . '.permohonan.reject', $permohonan->id) }}';
            return;
        }
        
        const inputs = Array.from(document.querySelectorAll('input[name^=\'dokumen[\']'));
        if (inputs.length === 0) return;
        
        const groups = {};
        inputs.forEach(input => {
            if (!groups[input.name]) groups[input.name] = [];
            groups[input.name].push(input);
        });
        
        const allSatisfied = Object.values(groups).every(group => {
            return group.some(input => input.type === 'hidden' || (input.type === 'radio' && input.checked));
        });
        
        if (allSatisfied) {
            this.decision = 'valid';
            this.actionUrl = '{{ route($role . '.permohonan.approve', $permohonan->id) }}';
        } else {
            this.decision = '';
            this.actionUrl = '';
        }
    },
    
    selectAllValidForActive() {
        const activeContainers = Array.from(document.querySelectorAll('[id^=\'member-container-\']'));
        const currentContainer = activeContainers[this.activeMemberIndex] || document;
        const validRadios = currentContainer.querySelectorAll('input[type=radio][value=\'Valid\'][name^=\'dokumen[\']');
        validRadios.forEach(r => {
            r.checked = true;
        });
        this.updateDecisionFromDocs();
    },
    
    selectAllValid() {
        this.selectAllValidForActive();
    },
    
    selectValidDecision() {
        this.selectAllValidForActive();
    },
    
    selectInvalidDecision() {
        this.decision = 'tidak_valid';
        this.actionUrl = '{{ route($role . '.permohonan.reject', $permohonan->id) }}';
    }
}" 
x-init="updateDecisionFromDocs()"
@change="updateDecisionFromDocs()"
class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- Left Column: Org Details & Member Documents List -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Organization Information -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base flex justify-between items-center">
                <h6 class="m-0 font-bold text-heading text-base">Informasi Organisasi Advokat</h6>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                    {{ $permohonan->status }}
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div class="border-b border-border-default border-dashed pb-3 md:col-span-2 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nama Organisasi Advokat</span>
                        <span class="text-heading font-bold text-lg">{{ $permohonan->organisasi?->nama_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor SK Pendirian Advokat</span>
                        <span class="text-heading font-semibold">{{ $permohonan->nomor_sk ?? '-' }}</span>
                    </div>

                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal SK Advokat</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_sk ? \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor HP Organisasi</span>
                        <span class="text-heading font-semibold">{{ $permohonan->no_hp_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Email Aktif Organisasi</span>
                        <span class="text-heading font-semibold">{{ $permohonan->email_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal Pengajuan</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members and Uploaded Documents -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base flex flex-wrap justify-between items-center gap-2">
                <h6 class="m-0 font-bold text-heading text-base">Berkas Dokumen Anggota Pemohon</h6>
                @if($permohonan->status === $targetStatus)
                    <button type="button" @click="selectAllValidForActive()" class="inline-flex items-center px-3 py-1.5 rounded-base text-xs font-bold text-white shadow-sm hover:shadow-md transition-all border cursor-pointer" style="background-color: #16a34a !important; color: #ffffff !important; border-color: #15803d !important;">
                        <i class="fa-solid fa-check-double mr-1.5"></i> {{ $permohonan->pemohons->count() > 1 ? 'Tandai Dokumen Anggota Ini Valid' : 'Tandai Semua Dokumen Valid' }}
                    </button>
                @endif
            </div>
            <div class="p-6">
                @if($permohonan->pemohons->count() > 1)
                    <!-- Member Tabs Navigation -->
                    <div class="mb-6">
                        <p class="text-xs font-semibold text-body-subtle uppercase tracking-wider mb-2">Pilih Anggota Pemohon untuk Diperiksa:</p>
                        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-border-default">
                            @foreach($permohonan->pemohons as $index => $p)
                                <button type="button" 
                                        @click="activeMemberIndex = {{ $index }}"
                                        :class="activeMemberIndex === {{ $index }} ? 'bg-brand text-white font-bold shadow-md border-brand' : 'bg-white text-heading hover:bg-neutral-secondary-soft font-semibold border-border-default'"
                                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs border transition-all flex-shrink-0 cursor-pointer">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold"
                                          :class="activeMemberIndex === {{ $index }} ? 'bg-white/20 text-white' : 'bg-neutral-secondary-medium text-heading'">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="truncate max-w-[150px]">{{ $p->nama_lengkap }}</span>
                                    @if($statusPerbaikan && $permohonan->status === $statusPerbaikan && $p->dokumenPersyaratan()->where('status_dokumen', 'Tidak Valid')->exists())
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping" title="Perlu validasi ulang"></span>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <!-- Quick Member Step Navigator (Anggota 1 dari N) -->
                        <div class="flex items-center justify-between mt-3 bg-neutral-secondary-soft px-4 py-2.5 rounded-xl border border-border-default text-xs">
                            <button type="button" 
                                    @click="if(activeMemberIndex > 0) activeMemberIndex--" 
                                    :disabled="activeMemberIndex === 0"
                                    :class="activeMemberIndex === 0 ? 'opacity-40 cursor-not-allowed text-body-subtle' : 'text-brand hover:underline font-bold cursor-pointer'"
                                    class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-chevron-left"></i> Anggota Sebelumnya
                            </button>
                            
                            <span class="text-heading font-bold">
                                Menampilkan Anggota <span x-text="activeMemberIndex + 1"></span> dari {{ $permohonan->pemohons->count() }}
                            </span>

                            <button type="button" 
                                    @click="if(activeMemberIndex < {{ $permohonan->pemohons->count() - 1 }}) activeMemberIndex++" 
                                    :disabled="activeMemberIndex === {{ $permohonan->pemohons->count() - 1 }}"
                                    :class="activeMemberIndex === {{ $permohonan->pemohons->count() - 1 }} ? 'opacity-40 cursor-not-allowed text-body-subtle' : 'text-brand hover:underline font-bold cursor-pointer'"
                                    class="inline-flex items-center gap-1.5">
                                Anggota Selanjutnya <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="space-y-8">
                    @forelse($permohonan->pemohons as $index => $pemohon)
                        <div x-show="activeMemberIndex === {{ $index }}" id="member-container-{{ $pemohon->id }}" class="border border-border-default rounded-xl p-6 bg-white space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-border-default pb-3 gap-2">
                            <div>
                                <h5 class="font-bold text-heading text-[16px]">{{ $pemohon->nama_lengkap }}</h5>
                                <div class="text-xs text-body-subtle font-mono mt-1 space-y-0.5">
                                    <div>NIK: {{ $pemohon->nik }}</div>
                                    <div>Email: {{ $pemohon->email }}</div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 ml-auto text-right">
                                @if($pemohon->status_verifikasi === 'Disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui Admin</span>
                                @elseif($pemohon->status_verifikasi === 'Ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak Admin</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-neutral-secondary-medium border border-border-default text-heading">Pending</span>
                                @endif

                                @if($permohonan->status_verifikator1 === 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui Verifikator 1</span>
                                @elseif($permohonan->status_verifikator1 === 'ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak Verifikator 1</span>
                                @endif

                                @if($permohonan->status_verifikator2 === 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui Verifikator 2</span>
                                @elseif($permohonan->status_verifikator2 === 'ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak Verifikator 2</span>
                                @endif

                                @if($permohonan->status_verifikator3 === 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui Verifikator 3</span>
                                @elseif($permohonan->status_verifikator3 === 'ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak Verifikator 3</span>
                                @endif

                                @if($permohonan->status_verifikator4 === 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui Verifikator 4</span>
                                @elseif($permohonan->status_verifikator4 === 'ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak Verifikator 4</span>
                                @endif
                            </div>
                        </div>

                        <!-- Documents list -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-medium">
                            @forelse($pemohon->dokumenPersyaratan as $doc)
                                @php
                                    $isValidAlready = ($statusPerbaikan && $permohonan->status === $statusPerbaikan && $doc->status_dokumen === 'Valid');
                                @endphp
                                <div class="p-4 rounded-lg space-y-3 border transition-all {{ $isValidAlready ? 'bg-gray-100/70 border-gray-200 opacity-75' : ($doc->status_dokumen === 'Tidak Valid' ? 'bg-amber-50/40 border-amber-300 shadow-sm ring-1 ring-amber-400/20' : 'bg-neutral-secondary-soft border-border-default/40') }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-heading font-semibold text-[13px] leading-snug">{{ $doc->masterPersyaratan->nama_persyaratan ?? 'Persyaratan' }}</div>
                                            @if($isValidAlready)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-success-soft text-fg-success-strong border border-border-success-subtle mt-1">
                                                    <i class="fa-solid fa-circle-check mr-1"></i> Valid (Sudah Disetujui)
                                                </span>
                                            @elseif($doc->status_dokumen === 'Tidak Valid')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-warning-soft text-fg-warning border border-border-warning-subtle mt-1">
                                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Perlu Validasi Ulang
                                                </span>
                                            @endif
                                        </div>
                                        @if($doc->file_path)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-brand/10 hover:bg-brand text-brand hover:text-white transition-all shadow-sm flex-shrink-0 text-[10px] font-bold">
                                                <i class="fa-solid fa-file-pdf"></i>
                                                <span>Lihat File</span>
                                            </a>
                                        @else
                                            <span class="text-body-subtle italic text-[11px] flex-shrink-0">Belum diunggah</span>
                                        @endif
                                    </div>
                                    
                                    @if($permohonan->status === $targetStatus)
                                        @if($isValidAlready)
                                            <!-- Already Valid: Grayed out and locks input, submits hidden input -->
                                            <input type="hidden" name="dokumen[{{ $doc->id }}]" value="Valid" form="verifikasiForm">
                                            <div class="flex items-center gap-2 py-1 text-xs text-gray-500 font-medium italic">
                                                <i class="fa-solid fa-lock text-gray-400"></i> Dokumen telah disetujui Valid.
                                            </div>
                                            @if($doc->keterangan)
                                                <div class="text-[11px] text-body-subtle italic">Catatan sebelumnya: {{ $doc->keterangan }}</div>
                                            @endif
                                        @else
                                            <!-- Interactive Choice for Repaired / Pending Document -->
                                            <div class="flex items-center gap-4 py-1">
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $doc->id }}]" value="Valid" form="verifikasiForm"
                                                           {{ old("dokumen.{$doc->id}") == 'Valid' ? 'checked' : '' }}
                                                           class="text-success focus:ring-success w-3.5 h-3.5">
                                                    <span class="text-[12px] text-heading font-bold">Valid</span>
                                                </label>
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $doc->id }}]" value="Tidak Valid" form="verifikasiForm"
                                                           {{ old("dokumen.{$doc->id}") == 'Tidak Valid' ? 'checked' : '' }}
                                                           class="text-danger focus:ring-danger w-3.5 h-3.5">
                                                    <span class="text-[12px] text-heading font-bold">Tidak Valid</span>
                                                </label>
                                            </div>
                                            <div>
                                                <input type="text" name="keterangan_dokumen[{{ $doc->id }}]" form="verifikasiForm"
                                                       value="{{ old("keterangan_dokumen.{$doc->id}", $doc->keterangan) }}"
                                                       placeholder="Catatan / Alasan (opsional)..."
                                                       class="block w-full rounded border border-border-default bg-white text-[11px] py-1.5 px-2.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all font-normal shadow-sm">
                                            </div>
                                        @endif
                                    @else
                                        <!-- Read-Only display -->
                                        <div class="flex items-center gap-1.5">
                                            @if($doc->status_dokumen === 'Valid')
                                                <span class="text-[10px] px-1.5 py-0.2 bg-success-soft text-fg-success-strong rounded font-bold">Valid</span>
                                            @elseif($doc->status_dokumen === 'Tidak Valid')
                                                <span class="text-[10px] px-1.5 py-0.2 bg-danger-soft text-fg-danger-strong rounded font-bold">Tidak Valid</span>
                                            @else
                                                <span class="text-[10px] px-1.5 py-0.2 bg-neutral-secondary-medium text-heading rounded font-bold">Pending</span>
                                            @endif
                                            @if($doc->keterangan)
                                                <span class="text-[11px] text-body-subtle italic">Catatan: {{ $doc->keterangan }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-2 text-center text-body-subtle italic py-2">Belum ada berkas dokumen yang diunggah.</div>
                            @endforelse
                        </div>
                        </div>
                    @empty
                        <div class="text-center text-body-subtle italic py-6">Tidak ada anggota terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Actions and Timeline -->
    <div class="space-y-8">
        
        <!-- Action Panel -->
        @if($permohonan->status === $targetStatus)
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default p-6">
                <h6 class="font-bold text-heading text-base border-b border-border-default pb-3 mb-2">Formulir Verifikasi Anda</h6>
                <p class="text-xs text-body leading-relaxed font-semibold">Tinjau seluruh dokumen di sebelah kiri. Berikan keputusan verifikasi untuk permohonan ini.</p>
                
                <div class="space-y-4">
                    <form id="verifikasiForm" :action="actionUrl" method="POST" class="space-y-4" onsubmit="handleVerifikasiSubmit(event, this)">
                        @csrf
                        <input type="hidden" name="catatan" :value="notes">
                        
                        <div>
                            <label class="block text-[13px] font-bold text-heading mb-2">Keputusan Verifikasi <span class="text-fg-danger">*</span></label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm text-heading">
                                    <input type="radio" name="decision" value="valid" x-model="decision" @change="selectValidDecision()" class="text-success focus:ring-success w-4 h-4" required>
                                    <span>Valid (Setujui)</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm text-heading">
                                    <input type="radio" name="decision" value="tidak_valid" x-model="decision" @change="selectInvalidDecision()" class="text-danger focus:ring-danger w-4 h-4" required>
                                    <span>Tidak Valid (Tolak)</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-heading mb-1.5">Catatan / Alasan</label>
                            <textarea x-model="notes" placeholder="Tulis catatan atau alasan (opsional)..." rows="4" 
                                      class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-sm text-heading py-2.5 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle"></textarea>
                        </div>

                        <button type="submit" :disabled="!decision"
                                class="w-full inline-flex justify-center items-center px-4 py-3.5 rounded-base text-sm font-bold shadow-md transition-all text-center border cursor-pointer"
                                :style="decision === 'valid' ? 'background-color: #16a34a !important; color: #ffffff !important; border-color: #15803d !important;' : (decision === 'tidak_valid' ? 'background-color: #dc2626 !important; color: #ffffff !important; border-color: #b91c1c !important;' : 'background-color: #e5e7eb !important; color: #6b7280 !important; border-color: #d1d5db !important; cursor: not-allowed !important;')"
                        >
                            <template x-if="decision === 'valid'">
                                <span class="flex items-center font-bold" style="color: #ffffff !important;"><i class="fa-solid fa-circle-check mr-2"></i> Setujui Permohonan</span>
                            </template>
                            <template x-if="decision === 'tidak_valid'">
                                <span class="flex items-center font-bold" style="color: #ffffff !important;"><i class="fa-solid fa-circle-xmark mr-2"></i> Tolak Permohonan</span>
                            </template>
                            <template x-if="!decision">
                                <span class="font-semibold" style="color: #6b7280 !important;">Pilih Keputusan (Valid / Tidak Valid) Terlebih Dahulu</span>
                            </template>
                        </button>
                    </form>
                </div>
            </div>
        @elseif($statusPerbaikan && $permohonan->status === $statusPerbaikan)
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default p-6 flex flex-col items-center justify-center text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-warning-soft flex items-center justify-center text-fg-warning shadow-inset">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <h6 class="font-bold text-heading text-base m-0">Menunggu Perbaikan Dokumen Pemohon</h6>
                <p class="text-xs text-body leading-relaxed max-w-[240px]">Terdapat dokumen anggota yang ditolak. Email notifikasi perbaikan telah dikirim ke organisasi &amp; pemohon.</p>
                <div class="pt-2 w-full">
                    @foreach($permohonan->pemohons as $pem)
                        @if($pem->status_verifikasi === 'Ditolak' || $pem->dokumenPersyaratan()->where('status_dokumen', 'Tidak Valid')->exists())
                            <a href="{{ route($role . '.permohonan.member-show', [$permohonan->id, $pem->id]) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-base text-xs font-bold bg-brand text-white shadow-sm hover:shadow-md transition-all border border-brand mb-2">
                                <i class="fa-solid fa-user-check mr-2"></i> Validasi Ulang: {{ $pem->nama_lengkap }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default p-6 flex flex-col items-center justify-center text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-neutral-secondary-medium flex items-center justify-center text-heading shadow-inset">
                    <i class="fa-solid fa-lock text-lg"></i>
                </div>
                <h6 class="font-bold text-heading text-sm m-0">Tindakan Dinonaktifkan</h6>
                <p class="text-xs text-body-subtle leading-relaxed max-w-[200px]">Permohonan tidak berada di antrean status Anda saat ini.</p>
            </div>
        @endif

        @if($permohonan->tanggal_verifikasi_fisik && !in_array($permohonan->status, ['Proses Pembuatan Surat', 'Surat Selesai', 'Selesai', 'Ditolak']))
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-3">
                <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-brand"></i> Jadwal Pengecekan Berkas Fisik
                </h6>
                <div class="text-xs space-y-2 text-body">
                    <div class="flex justify-between">
                        <span class="font-medium text-body-subtle">Hari:</span>
                        <span class="font-bold text-heading">{{ $permohonan->hari_verifikasi_fisik ?? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->isoFormat('dddd') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-body-subtle">Tanggal:</span>
                        <span class="font-bold text-heading">{{ \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Riwayat Status Timeline -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6">
            <h6 class="font-bold text-heading mb-4">Riwayat Status Permohonan</h6>
            <div class="max-h-[300px] overflow-y-auto pr-2 pl-1 py-1 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                <div class="relative border-l border-brand ml-2 space-y-6 pb-2" style="border-left: 2px solid var(--color-brand, #8b1e1e); margin-left: 8px; position: relative;">
                    @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $riwayat)
                    <div class="relative pl-6" style="position: relative; padding-left: 24px;">
                        <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-brand -translate-x-1/2" style="background-color: var(--color-brand, #8b1e1e);"></div>
                        <div class="text-[13px] text-heading font-bold">{{ $riwayat->status_baru }}</div>
                        <div class="text-[11px] text-body-subtle">{{ \Carbon\Carbon::parse($riwayat->changed_at)->translatedFormat('d M Y - H:i') }}</div>
                        @php
                            $isPhysicalNote = $riwayat->keterangan && (
                                str_contains(strtolower($riwayat->keterangan), 'mohon bawa') || 
                                str_contains(strtolower($riwayat->keterangan), 'berkas fisik')
                            );
                        @endphp
                        @if($riwayat->keterangan && !str_starts_with($riwayat->status_baru, 'Menunggu Verifikasi Verifikator') && !($isPhysicalNote && !in_array($riwayat->status_baru, ['Menentukan Jadwal Berkas Fisik', 'Verifikasi Berkas Fisik'])))
                            <div class="text-[12px] text-body mt-1.5 bg-white p-2 rounded border border-border-default shadow-sm font-medium whitespace-normal break-words">{{ $riwayat->keterangan }}</div>
                        @endif
                    </div>
                    @empty
                    <div class="pl-4 text-body-subtle text-xs italic">Belum ada riwayat.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function handleVerifikasiSubmit(e, form) {
    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
        return;
    }
    const isApprove = form.action && form.action.includes('approve');
    const title = isApprove ? 'Memproses Persetujuan Verifikator...' : 'Memproses Catatan Perbaikan & Mengirim Email...';
    const sub   = isApprove ? 'Harap tunggu, verifikasi permohonan sedang disetujui.' : 'Harap tunggu, notifikasi perbaikan berkas sedang dikirimkan.';
    showGlobalLoading(title, sub);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
    }
}
</script>
@endpush
