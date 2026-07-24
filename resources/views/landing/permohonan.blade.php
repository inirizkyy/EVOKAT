@extends('layouts.frontend')
@section('title', 'Formulir Permohonan Sumpah Advokat')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Pengajuan Permohonan</h1>
            <p class="text-body-subtle text-lg">Lengkapi data Organisasi Advokat dan daftarkan anggota pemohon.</p>
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

            <!-- Step Indicator (Step 1 active) -->
            <div class="flex items-start justify-center mb-12">
                <div class="flex items-start space-x-2 sm:space-x-4">
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-brand text-white ring-4 sm:ring-8 ring-brand-softer">
                            1
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-brand">Data Organisasi &amp; Anggota</span>
                    </div>
                    
                    <div class="h-[4px] w-8 sm:w-16 bg-border-default rounded-full mt-[24px] sm:mt-[26px]">
                        <div class="h-full bg-brand transition-all duration-500 rounded-full" style="width: 0%"></div>
                    </div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-white text-body-subtle border border-border-default">
                            2
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-body-subtle">Upload Dokumen</span>
                    </div>

                    <div class="h-[4px] w-8 sm:w-16 bg-border-default rounded-full mt-[24px] sm:mt-[26px]">
                        <div class="h-full bg-brand transition-all duration-500 rounded-full" style="width: 0%"></div>
                    </div>
                    
                    <div class="flex flex-col items-center w-24 sm:w-28">
                        <div class="flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full text-lg sm:text-xl font-bold transition-all duration-300 shadow-sm bg-white text-body-subtle border border-border-default">
                            3
                        </div>
                        <span class="mt-3 sm:mt-4 text-[14px] sm:text-[16px] font-bold text-center text-body-subtle">Submit</span>
                    </div>
                </div>
            </div>

            <div class="text-center mb-10 border-b border-border-default pb-6">
                <h3 class="font-['Playfair_Display'] text-2xl font-bold text-heading">Data Organisasi Advokat</h3>
                <p class="text-[15px] text-body-subtle mt-2">Masukkan informasi resmi Organisasi Advokat pengusung.</p>
            </div>

            <form action="{{ url('/permohonan') }}" method="POST" id="permohonan-form" x-data="permohonanForm()" enctype="multipart/form-data"
                  data-loading-title="Menyimpan Data Organisasi &amp; Anggota..."
                  data-loading-sub="Harap tunggu, Anda akan diarahkan ke halaman kelengkapan berkas.">
                @csrf
                
                <!-- DATA ORGANISASI -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-12">
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Organisasi Advokat <span class="text-fg-danger">*</span></label>
                        <select name="organization_id" id="orgSelect" required @change="checkSelect($event.target.value)"
                                class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all">
                            <option value="">-- Pilih Organisasi --</option>
                            @foreach($organisasi as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                    {{ $org->nama_organisasi }} @if($org->singkatan) ({{ $org->singkatan }}) @endif
                                </option>
                            @endforeach
                            @if(old('organization_id') && is_string(old('organization_id')) && str_starts_with(old('organization_id'), 'new:'))
                                @php $oldProposedName = substr(old('organization_id'), 4); @endphp
                                <option value="{{ old('organization_id') }}" selected>{{ $oldProposedName }} (Usulan Baru)</option>
                            @endif
                            <option value="other" class="text-brand font-bold">+ Organisasi belum tersedia? Ajukan Penambahan</option>
                        </select>
                        @error('organization_id')
                            <div class="text-xs text-fg-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Nomor Surat Pengantar Organisasi <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="nomor_surat_pengantar" value="{{ old('nomor_surat_pengantar') }}" required placeholder="Contoh: 001/ORG/VII/2026">
                        @error('nomor_surat_pengantar')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Tanggal Surat Pengantar <span class="text-fg-danger">*</span></label>
                        <input type="date" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="tanggal_surat_pengantar" value="{{ old('tanggal_surat_pengantar') }}" required>
                        @error('tanggal_surat_pengantar')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Nomor HP Organisasi <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="no_hp_organisasi" value="{{ old('no_hp_organisasi') }}" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Email Aktif Organisasi <span class="text-fg-danger">*</span></label>
                        <input type="email" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="email_organisasi" value="{{ old('email_organisasi') }}" required placeholder="email.organisasi@example.com">
                    </div>
                    <div class="group md:col-span-2">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">Perihal Surat Pengantar <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[16px] text-heading py-4 px-5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="perihal_surat_pengantar" value="{{ old('perihal_surat_pengantar') }}" required placeholder="Contoh: Permohonan Sumpah Advokat Baru">
                        @error('perihal_surat_pengantar')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="group md:col-span-2">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">
                            Upload Surat Pengantar Organisasi (PDF) <span class="text-fg-danger">*</span>
                        </label>
                        @php
                            $tempSurat = session('temp_permohonan_files.file_surat_pengantar');
                            $tempSuratPath = is_array($tempSurat) ? ($tempSurat['path'] ?? null) : $tempSurat;
                            $tempSuratName = is_array($tempSurat) ? ($tempSurat['name'] ?? basename($tempSuratPath)) : basename($tempSuratPath);
                            $hasTempSurat = $tempSuratPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($tempSuratPath);
                        @endphp
                        <label for="file_surat_pengantar" class="flex items-center gap-4 w-full p-2 rounded-2xl border border-border-default-medium bg-neutral-primary-soft hover:bg-neutral-secondary-soft transition-all cursor-pointer">
                            <span class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand text-white text-sm font-bold shadow-sm flex-shrink-0 hover:bg-brand/90 transition-all">
                                Choose File
                            </span>
                            <span id="label_surat_pengantar" class="text-[14.5px] font-semibold text-heading truncate flex-grow">
                                {{ $hasTempSurat ? $tempSuratName : 'No file chosen' }}
                            </span>
                            <input id="file_surat_pengantar" type="file" class="hidden" name="file_surat_pengantar" accept=".pdf" {{ $hasTempSurat ? '' : 'required' }} onchange="document.getElementById('label_surat_pengantar').textContent = this.files[0] ? this.files[0].name : '{{ $hasTempSurat ? addslashes($tempSuratName) : "No file chosen" }}'">
                        </label>
                        @if($hasTempSurat)
                            <input type="hidden" name="has_temp_file_surat_pengantar" value="1">
                        @endif
                        <p class="text-xs text-fg-danger font-medium mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Format: PDF, maks. 2MB</p>
                        @error('file_surat_pengantar')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">
                            Upload SK Pendirian Advokat (PDF) <span class="text-fg-danger">*</span>
                        </label>
                        @php
                            $tempPendirian = session('temp_permohonan_files.file_sk_pendirian');
                            $tempPendirianPath = is_array($tempPendirian) ? ($tempPendirian['path'] ?? null) : $tempPendirian;
                            $tempPendirianName = is_array($tempPendirian) ? ($tempPendirian['name'] ?? basename($tempPendirianPath)) : basename($tempPendirianPath);
                            $hasTempPendirian = $tempPendirianPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($tempPendirianPath);
                        @endphp
                        <label for="file_sk_pendirian" class="flex items-center gap-4 w-full p-2 rounded-2xl border border-border-default-medium bg-neutral-primary-soft hover:bg-neutral-secondary-soft transition-all cursor-pointer">
                            <span class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand text-white text-sm font-bold shadow-sm flex-shrink-0 hover:bg-brand/90 transition-all">
                                Choose File
                            </span>
                            <span id="label_sk_pendirian" class="text-[14.5px] font-semibold text-heading truncate flex-grow">
                                {{ $hasTempPendirian ? $tempPendirianName : 'No file chosen' }}
                            </span>
                            <input id="file_sk_pendirian" type="file" class="hidden" name="file_sk_pendirian" accept=".pdf" {{ $hasTempPendirian ? '' : 'required' }} onchange="document.getElementById('label_sk_pendirian').textContent = this.files[0] ? this.files[0].name : '{{ $hasTempPendirian ? addslashes($tempPendirianName) : "No file chosen" }}'">
                        </label>
                        @if($hasTempPendirian)
                            <input type="hidden" name="has_temp_file_sk_pendirian" value="1">
                        @endif
                        <p class="text-xs text-fg-danger font-medium mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Format: PDF, maks. 2MB</p>
                        @error('file_sk_pendirian')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="group">
                        <label class="block text-[15px] font-bold text-heading mb-3 group-focus-within:text-brand transition-colors">
                            Upload SK Kepengurusan Advokat (PDF) <span class="text-fg-danger">*</span>
                        </label>
                        @php
                            $tempKepengurusan = session('temp_permohonan_files.file_sk_kepengurusan');
                            $tempKepengurusanPath = is_array($tempKepengurusan) ? ($tempKepengurusan['path'] ?? null) : $tempKepengurusan;
                            $tempKepengurusanName = is_array($tempKepengurusan) ? ($tempKepengurusan['name'] ?? basename($tempKepengurusanPath)) : basename($tempKepengurusanPath);
                            $hasTempKepengurusan = $tempKepengurusanPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($tempKepengurusanPath);
                        @endphp
                        <label for="file_sk_kepengurusan" class="flex items-center gap-4 w-full p-2 rounded-2xl border border-border-default-medium bg-neutral-primary-soft hover:bg-neutral-secondary-soft transition-all cursor-pointer">
                            <span class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand text-white text-sm font-bold shadow-sm flex-shrink-0 hover:bg-brand/90 transition-all">
                                Choose File
                            </span>
                            <span id="label_sk_kepengurusan" class="text-[14.5px] font-semibold text-heading truncate flex-grow">
                                {{ $hasTempKepengurusan ? $tempKepengurusanName : 'No file chosen' }}
                            </span>
                            <input id="file_sk_kepengurusan" type="file" class="hidden" name="file_sk_kepengurusan" accept=".pdf" {{ $hasTempKepengurusan ? '' : 'required' }} onchange="document.getElementById('label_sk_kepengurusan').textContent = this.files[0] ? this.files[0].name : '{{ $hasTempKepengurusan ? addslashes($tempKepengurusanName) : "No file chosen" }}'">
                        </label>
                        @if($hasTempKepengurusan)
                            <input type="hidden" name="has_temp_file_sk_kepengurusan" value="1">
                        @endif
                        <p class="text-xs text-fg-danger font-medium mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Format: PDF, maks. 2MB</p>
                        @error('file_sk_kepengurusan')<div class="text-xs text-fg-danger mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- DAFTAR ANGGOTA SECTION -->
                <div class="border-t border-border-default pt-8 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h4 class="font-['Playfair_Display'] text-2xl font-bold text-heading">Daftar Anggota</h4>
                            <p class="text-[14px] text-body-subtle mt-1">Tambahkan anggota yang akan diajukan untuk disumpah.</p>
                        </div>
                        <button type="button" @click="addMember()" class="inline-flex justify-center items-center px-5 py-2.5 rounded-full text-[14px] font-bold bg-brand text-white shadow-sm hover:shadow-md active:translate-y-0 transition-all border border-brand-softer">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Anggota
                        </button>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(member, index) in members" :key="index">
                            <div class="bg-white border border-border-default rounded-2xl p-6 sm:p-8 relative shadow-sm hover:shadow-md transition-all" style="position: relative;">
                                <!-- Remove Button -->
                                <div style="position: absolute; right: 24px; top: 24px; z-index: 10;">
                                    <button type="button" @click="removeMember(index)" class="w-8 h-8 rounded-full bg-danger-soft hover:bg-fg-danger hover:text-white border border-border-danger-subtle text-fg-danger-strong flex items-center justify-center transition-all" title="Hapus Anggota">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2 mb-6 text-brand font-bold text-lg border-b border-border-default border-dashed pb-3">
                                    <span class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center text-sm border border-brand/20" x-text="index + 1"></span>
                                    <span>Anggota <span x-text="index + 1"></span></span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">NIK <span class="text-fg-danger">*</span></label>
                                        <input type="text" class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all font-mono"
                                               x-model="member.nik" :name="`members[${index}][nik]`" required minlength="16" maxlength="16" placeholder="NIK 16 Digit">
                                    </div>
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">Nama Lengkap &amp; Gelar <span class="text-fg-danger">*</span></label>
                                        <input type="text" class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                               x-model="member.nama_lengkap" :name="`members[${index}][nama_lengkap]`" required placeholder="Contoh: Nama, S.H.">
                                    </div>
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">Tempat Lahir <span class="text-fg-danger">*</span></label>
                                        <input type="text" class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                               x-model="member.tempat_lahir" :name="`members[${index}][tempat_lahir]`" required placeholder="Kota Kelahiran">
                                    </div>
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">Tanggal Lahir <span class="text-fg-danger">*</span></label>
                                        <input type="date" class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                               x-model="member.tanggal_lahir" :name="`members[${index}][tanggal_lahir]`" required>
                                    </div>
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">Jenis Kelamin <span class="text-fg-danger">*</span></label>
                                        <select class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                                x-model="member.jenis_kelamin" :name="`members[${index}][jenis_kelamin]`" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[14px] font-bold text-heading mb-2">Email Aktif Anggota <span class="text-fg-danger">*</span></label>
                                        <input type="email" class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                               x-model="member.email" :name="`members[${index}][email]`" required placeholder="email.anggota@example.com">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[14px] font-bold text-heading mb-2">Alamat Lengkap Anggota <span class="text-fg-danger">*</span></label>
                                        <textarea class="block w-full rounded-lg border border-border-default bg-neutral-primary-soft text-[15px] py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all"
                                                  x-model="member.alamat" :name="`members[${index}][alamat]`" required rows="3" placeholder="Tuliskan alamat lengkap anggota..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-border-default flex justify-end">
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all group">
                        Lanjut ke Upload Dokumen <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Decoupled Modal: Pengajuan Organisasi -->
    <div x-data="{ 
            show: false, 
            proposeName: '', 
            proposeError: '', 
            submitProposal() {
                if (!this.proposeName.trim()) {
                    this.proposeError = 'Nama organisasi wajib diisi.';
                    return;
                }
                const name = this.proposeName.trim();
                const newValue = 'new:' + name;
                const select = document.getElementById('orgSelect');
                
                let existingOpt = Array.from(select.options).find(opt => opt.value.toLowerCase() === newValue.toLowerCase() || opt.text.toLowerCase().startsWith(name.toLowerCase()));
                
                if (existingOpt) {
                    select.value = existingOpt.value;
                } else {
                    const opt = document.createElement('option');
                    opt.value = newValue;
                    opt.textContent = name + ' (Usulan Baru)';
                    select.insertBefore(opt, select.lastElementChild);
                    select.value = newValue;
                }
                
                this.show = false;
                this.proposeName = '';
                this.proposeError = '';
            }
         }"
         x-show="show"
         @open-propose-modal.window="show = true; proposeName = ''; proposeError = '';"
         class="fixed inset-0 bg-neutral-secondary-strong/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-cloak
         style="display: none;">
        <div class="bg-white rounded-xl shadow-xl border border-border-default max-w-md w-full p-6 space-y-4 relative"
             @click.stop
             @click.outside="show = false">
            
            <button type="button" @click="show = false" class="absolute top-4 right-4 text-body-subtle hover:text-heading transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div>
                <h4 class="font-['Playfair_Display'] text-xl font-bold text-heading">Ajukan Organisasi Baru</h4>
                <p class="text-xs text-body-subtle mt-1">Ajukan organisasi advokat jika belum terdaftar dalam sistem. Usulan akan diverifikasi oleh Admin.</p>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-[13px] font-medium text-heading mb-1.5">Nama Organisasi <span class="text-fg-danger">*</span></label>
                    <input type="text" x-model="proposeName" 
                           @keydown.enter.prevent="submitProposal()"
                           class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all"
                           placeholder="Contoh: Perhimpunan Advokat Indonesia (PERADI)">
                    <p class="text-[12px] text-fg-danger font-medium mt-1.5 leading-normal">
                        <i class="fa-solid fa-circle-info text-fg-danger mr-1"></i>Format penulisan: <strong>Nama Organisasi (Nama Singkatan Organisasi)</strong>
                    </p>
                </div>
                <div x-show="proposeError" class="text-xs text-fg-danger font-medium" x-text="proposeError"></div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="show = false" 
                        class="px-4 py-2 rounded-base text-sm font-medium border border-border-default text-body hover:bg-neutral-secondary-soft transition-all">
                    Batal
                </button>
                <button type="button" @click="submitProposal()"
                        class="inline-flex items-center px-4 py-2 rounded-base text-sm font-bold bg-brand text-white border border-brand hover:opacity-95 transition-all">
                    Ajukan
                </button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('permohonanForm', () => ({
            members: {!! json_encode(old('members', [['nik' => '', 'nama_lengkap' => '', 'tempat_lahir' => '', 'tanggal_lahir' => '', 'jenis_kelamin' => '', 'email' => '', 'alamat' => '']])) !!},
            addMember() {
                this.members.push({ nik: '', nama_lengkap: '', tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', email: '', alamat: '' });
            },
            removeMember(index) {
                if (this.members.length > 1) {
                    this.members.splice(index, 1);
                } else {
                    alert('Minimal harus terdapat 1 anggota pemohon.');
                }
            },
            checkSelect(val) {
                if (val === 'other') {
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('open-propose-modal'));
                    }, 50);
                    document.getElementById('orgSelect').value = '';
                }
            }
        }));
    });
</script>
@endpush
