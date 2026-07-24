@extends('layouts.frontend')
@section('title', 'Unggah Berkas Persyaratan')

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-all duration-300">
    <div class="relative flex flex-col items-center">
        <i class="fa-solid fa-spinner fa-spin text-5xl text-brand mb-4"></i>
        <p class="text-lg font-bold text-heading">Sedang mengunggah berkas...</p>
        <p class="text-sm text-body-subtle mt-1">Harap tunggu, proses ini memerlukan waktu beberapa saat.</p>
    </div>
</div>

<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Unggah Berkas</h1>
            <p class="text-body-subtle text-lg">Unggah berkas kelengkapan administrasi untuk anggota.</p>
        </div>
        
        <div id="form-container" class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-6 sm:p-10 lg:p-12 relative overflow-hidden">
            
            <!-- Background Decorative Elements -->
            <div class="absolute right-0 top-0 text-brand opacity-5 text-[15rem] pointer-events-none select-none -z-10 translate-x-1/4 -translate-y-1/4">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>

            @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <p class="text-[14px]">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong flex items-start gap-3">
                <i class="fa-solid fa-circle-xmark mt-1"></i>
                <p class="text-[14px]">{{ session('error') }}</p>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">
                <ul class="list-disc list-inside text-[14px] space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Step Indicator (Step 2 active) -->
            <div class="flex items-start justify-center mb-12">
                <div class="flex items-start space-x-2 sm:space-x-4">
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold bg-brand text-white">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-brand">Data Organisasi &amp; Anggota</span>
                    </div>
                    
                    <div class="h-[4px] w-8 sm:w-16 bg-brand rounded-full mt-[24px] sm:mt-[26px]"></div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold bg-brand text-white ring-4 sm:ring-8 ring-brand-softer">
                            2
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-brand">Upload Dokumen</span>
                    </div>

                    <div class="h-[4px] w-8 sm:w-16 bg-border-default rounded-full mt-[24px] sm:mt-[26px]"></div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold bg-white text-body-subtle border border-border-default">
                            3
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-body-subtle">Submit</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('permohonan.store-dokumen-upload', [$permohonan->nomor_permohonan, $pemohon->id]) }}" method="POST" enctype="multipart/form-data" id="upload-form"
                  data-loading-title="Mengunggah Berkas Dokumen..."
                  data-loading-sub="Proses unggah file memerlukan waktu beberapa saat tergantung ukuran berkas."
                  onsubmit="showLoading()">
                @csrf

                <!-- Member Profil Card -->
                <div class="mb-8 p-6 rounded-2xl bg-white border border-border-default shadow-sm flex flex-col lg:flex-row items-stretch gap-8">
                    <!-- Profil Info (Left) -->
                    <div class="flex-grow space-y-4">
                        <h4 class="font-['Playfair_Display'] text-xl font-bold text-heading pb-2.5 border-b border-border-default flex items-center gap-2">
                            <i class="fa-regular fa-user text-brand"></i> Profil Anggota
                        </h4>
                        <div class="space-y-3.5 text-[14.5px] font-medium text-heading">
                            <div class="flex flex-col sm:flex-row sm:items-center border-b border-border-default border-dashed pb-2">
                                <span class="text-body-subtle sm:w-2/5">Nama Lengkap &amp; Gelar</span>
                                <span class="text-heading font-bold sm:w-3/5">{{ $pemohon->nama_lengkap }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center border-b border-border-default border-dashed pb-2">
                                <span class="text-body-subtle sm:w-2/5">NIK</span>
                                <span class="text-heading font-bold sm:w-3/5 font-mono">{{ $pemohon->nik }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center border-b border-border-default border-dashed pb-2">
                                <span class="text-body-subtle sm:w-2/5">Tempat, Tanggal Lahir</span>
                                <span class="text-heading font-bold sm:w-3/5">{{ $pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center pb-1">
                                <span class="text-body-subtle sm:w-2/5">Email Anggota</span>
                                <span class="text-heading font-bold sm:w-3/5">{{ $pemohon->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Unggah Pas Foto (Right) -->
                    @php
                        $pasFotoPersyaratan = $persyaratan->first(function($p) {
                            return strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false;
                        });
                    @endphp
                    @if($pasFotoPersyaratan)
                        @php
                            $uploaded = $pemohon->dokumenPersyaratan->firstWhere('persyaratan_id', $pasFotoPersyaratan->id);
                            $isValid = ($uploaded && $uploaded->status_dokumen == 'Valid');
                        @endphp
                        <div class="w-full lg:w-[320px] flex-shrink-0 flex flex-col justify-between p-6 rounded-2xl border {{ $isValid ? 'bg-gray-100 border-gray-200' : 'bg-white border-border-default shadow-sm' }} relative overflow-hidden min-h-[220px]">
                            <div class="absolute -right-4 -bottom-4 text-brand opacity-5 text-[6rem] pointer-events-none select-none z-0">
                                <i class="fa-regular fa-image"></i>
                            </div>
                            
                            <div class="relative z-10 w-full mb-4">
                                <label class="block text-[15px] font-bold {{ $isValid ? 'text-gray-500' : 'text-heading' }} mb-1 relative z-10">
                                    {{ $pasFotoPersyaratan->nama_persyaratan }} 
                                    @if($pasFotoPersyaratan->is_required) <span class="text-fg-danger">*</span> @endif
                                </label>
                                <p class="text-[12px] {{ $isValid ? 'text-gray-400' : 'text-body' }} font-medium leading-relaxed mb-1.5 relative z-10">{{ $pasFotoPersyaratan->deskripsi }}</p>
                            </div>
                            
                            <div class="relative z-10 w-full mt-auto space-y-3">
                                @if($uploaded)
                                    <div class="flex items-center gap-3 p-2 rounded-lg bg-neutral-primary-soft border border-border-default mb-2">
                                        <img src="{{ asset('storage/'.$uploaded->file_path) }}" alt="Preview" class="w-10 h-14 object-cover rounded border border-border-default">
                                        <div class="flex-grow min-w-0">
                                            <div class="truncate text-[12px] font-bold text-heading" title="{{ basename($uploaded->file_path) }}">{{ basename($uploaded->file_path) }}</div>
                                            <div class="mt-1 flex items-center gap-1">
                                                @if($uploaded->status_dokumen == 'Valid')
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-success-soft text-fg-success-strong border border-border-success-subtle">✔ Valid</span>
                                                @elseif($uploaded->status_dokumen == 'Tidak Valid')
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-danger-soft text-fg-danger-strong border border-border-danger-subtle" title="Keterangan: {{ $uploaded->keterangan }}">✖ Ditolak</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-neutral-secondary-medium text-heading border border-border-default">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($uploaded->status_dokumen == 'Tidak Valid' && $uploaded->keterangan)
                                        <div class="p-2.5 rounded-lg bg-danger-soft border border-border-danger-subtle text-[11.5px] text-fg-danger-strong font-medium mb-1.5">
                                            <strong>Catatan Penolakan:</strong> {{ $uploaded->keterangan }}
                                        </div>
                                    @endif
                                @endif

                                @if(!$isValid)
                                    @php
                                        $tempPasFoto = session("temp_dokumen_upload.{$pemohon->id}.{$pasFotoPersyaratan->id}");
                                        $hasTempPasFoto = $tempPasFoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($tempPasFoto);
                                        $origPasFotoLabel = $hasTempPasFoto ? 'Ganti Foto (Tersimpan)...' : ($uploaded ? 'Ganti Foto...' : 'Unggah Foto...');
                                    @endphp
                                    <div id="file-error-{{ $pasFotoPersyaratan->id }}" class="hidden mb-2 p-2.5 rounded-lg bg-danger-soft border border-border-danger-subtle text-[12px] text-fg-danger-strong font-medium"></div>
                                    @if($hasTempPasFoto)
                                        <div class="flex items-center gap-2 p-2 rounded-lg bg-success-soft border border-border-success-subtle mb-2">
                                            <i class="fa-solid fa-file-circle-check text-fg-success-strong text-sm"></i>
                                            <span class="text-[12px] font-bold text-fg-success-strong truncate" title="{{ basename($tempPasFoto) }}">
                                                Tersimpan sementara: {{ basename($tempPasFoto) }}
                                            </span>
                                        </div>
                                    @endif
                                    <label for="dokumen_{{ $pasFotoPersyaratan->id }}" class="flex items-center justify-center w-full px-4 py-2.5 border-2 border-border-default-medium border-dashed bg-neutral-primary hover:bg-neutral-secondary-soft transition-colors hover:border-brand rounded-xl cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 w-full overflow-hidden">
                                            <i class="fa-solid fa-upload text-brand/70 text-base flex-shrink-0"></i>
                                            <span id="dokumen-name-{{ $pasFotoPersyaratan->id }}" class="text-[13px] font-bold text-body hover:text-brand truncate w-full text-center transition-colors">
                                                {{ $origPasFotoLabel }}
                                            </span>
                                        </div>
                                        <input id="dokumen_{{ $pasFotoPersyaratan->id }}" type="file" class="hidden" name="dokumen[{{ $pasFotoPersyaratan->id }}]" {{ ($pasFotoPersyaratan->is_required && !$uploaded && !$hasTempPasFoto) ? 'required' : '' }} accept=".jpg,.jpeg,.png" data-original-label="{{ $origPasFotoLabel }}" onchange="validateFileSize(this, {{ $pasFotoPersyaratan->id }})">
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    @foreach($persyaratan as $p)
                        @php
                            $isPasFoto = (strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false);
                            if ($isPasFoto) continue;

                            $uploaded = $pemohon->dokumenPersyaratan->firstWhere('persyaratan_id', $p->id);
                            $isValid = ($uploaded && $uploaded->status_dokumen == 'Valid');
                        @endphp
                        <div class="{{ $isValid ? 'bg-gray-100 opacity-75 border-gray-200' : 'bg-white border-border-default' }} rounded-2xl p-6 sm:p-7 border shadow-sm hover:shadow-md transition-all group flex flex-col h-full relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 {{ $isValid ? 'text-gray-400' : 'text-brand' }} opacity-5 text-[7rem] group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 pointer-events-none select-none z-0">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>

                            <label class="block text-[16px] font-bold {{ $isValid ? 'text-gray-500' : 'text-heading' }} mb-2 relative z-10">
                                {{ $p->nama_persyaratan }} 
                                @if($p->is_required) <span class="text-fg-danger">*</span> @endif
                            </label>
                            <p class="text-[13.5px] {{ $isValid ? 'text-gray-400' : 'text-body' }} font-medium leading-relaxed mb-4 relative z-10">{{ $p->deskripsi }}</p>
                            
                            <!-- File Link and Upload Option -->
                            <div class="mt-auto relative z-10 w-full space-y-3">
                                @if($uploaded)
                                    @php
                                        $isPasFoto = (strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false);
                                        $fileIcon = $isPasFoto ? 'fa-regular fa-image' : 'fa-regular fa-file-pdf';
                                    @endphp
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-neutral-primary-soft border border-border-default text-[13px] font-semibold text-heading mb-2">
                                        <span class="truncate max-w-[200px]" title="{{ basename($uploaded->file_path) }}">
                                            <i class="{{ $fileIcon }} text-brand-strong mr-1.5"></i>{{ basename($uploaded->file_path) }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            @if($uploaded->status_dokumen == 'Valid')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-success-soft text-fg-success-strong border border-border-success-subtle">✔ Valid</span>
                                            @elseif($uploaded->status_dokumen == 'Tidak Valid')
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-danger-soft text-fg-danger-strong border border-border-danger-subtle" title="Keterangan: {{ $uploaded->keterangan }}">✖ Ditolak</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-neutral-secondary-medium text-heading border border-border-default">Pending</span>
                                            @endif
                                            <a href="{{ asset('storage/'.$uploaded->file_path) }}" target="_blank" class="w-8 h-8 rounded-full bg-white border border-border-default hover:text-brand flex items-center justify-center shadow-sm">
                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @if($uploaded->status_dokumen == 'Tidak Valid' && $uploaded->keterangan)
                                        <div class="mb-3 p-3 rounded-lg bg-danger-soft border border-border-danger-subtle text-[12.5px] text-fg-danger-strong font-medium">
                                            <strong>Catatan Penolakan:</strong> {{ $uploaded->keterangan }}
                                        </div>
                                    @endif
                                @endif

                                @if(!$isValid)
                                    @php
                                        $isPasFoto = (strpos(strtolower($p->nama_persyaratan), 'pas foto') !== false);
                                        $acceptMime = $isPasFoto ? '.jpg,.jpeg,.png' : '.pdf';
                                        $fileLabel = $isPasFoto ? 'JPG, JPEG, PNG' : 'PDF';
                                        $tempDoc = session("temp_dokumen_upload.{$pemohon->id}.{$p->id}");
                                        $hasTempDoc = $tempDoc && \Illuminate\Support\Facades\Storage::disk('public')->exists($tempDoc);
                                        $origDocLabel = $hasTempDoc ? "Ganti File (Tersimpan {$fileLabel})..." : ($uploaded ? "Ganti File ({$fileLabel})..." : "Pilih File ({$fileLabel})...");
                                    @endphp
                                    <div id="file-error-{{ $p->id }}" class="hidden mb-2 p-2.5 rounded-lg bg-danger-soft border border-border-danger-subtle text-[12px] text-fg-danger-strong font-medium"></div>
                                    @if($hasTempDoc)
                                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-success-soft border border-border-success-subtle text-[12.5px] font-bold text-fg-success-strong mb-2">
                                            <i class="fa-solid fa-file-circle-check text-base"></i>
                                            <span class="truncate" title="{{ basename($tempDoc) }}">Tersimpan sementara: {{ basename($tempDoc) }}</span>
                                        </div>
                                    @endif
                                    <label for="dokumen_{{ $p->id }}" class="flex items-center justify-center w-full px-4 py-3 border-2 border-border-default-medium border-dashed bg-neutral-primary hover:bg-neutral-secondary-soft transition-colors hover:border-brand group-hover:bg-white rounded-xl cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 w-full overflow-hidden">
                                            <i class="fa-solid fa-upload text-brand/70 text-lg flex-shrink-0"></i>
                                            <span id="dokumen-name-{{ $p->id }}" class="text-[14px] font-bold text-body group-hover:text-brand truncate w-full text-center transition-colors">
                                                {{ $origDocLabel }}
                                            </span>
                                        </div>
                                        <input id="dokumen_{{ $p->id }}" type="file" class="hidden" name="dokumen[{{ $p->id }}]" {{ ($p->is_required && !$uploaded && !$hasTempDoc) ? 'required' : '' }} accept="{{ $acceptMime }}" data-original-label="{{ $origDocLabel }}" onchange="validateFileSize(this, {{ $p->id }})">
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 pt-6 border-t border-border-default flex flex-col-reverse sm:flex-row justify-between items-center gap-4">
                    @if($permohonan->status !== 'Draft')
                        <a href="{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 rounded-full text-[15px] font-bold bg-white text-heading border border-border-default-strong shadow-sm hover:bg-neutral-secondary-soft hover:text-brand transition-all">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Batal / Kembali ke Tracking
                        </a>
                    @else
                        <a href="{{ route('permohonan.dokumen-list', $permohonan->nomor_permohonan) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 rounded-full text-[15px] font-bold bg-white text-heading border border-border-default-strong shadow-sm hover:bg-neutral-secondary-soft hover:text-brand transition-all">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Anggota
                        </a>
                    @endif
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer">
                        Simpan Berkas <i class="fa-solid fa-floppy-disk ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function showLoading() {
        showGlobalLoading('Mengunggah Berkas Dokumen...', 'Proses unggah file memerlukan waktu beberapa saat tergantung ukuran berkas.');
    }
</script>
@endpush
