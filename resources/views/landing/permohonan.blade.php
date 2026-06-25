@extends('layouts.frontend')
@section('title', 'Formulir Permohonan Sumpah Advokat')

@section('content')
<section class="py-12 lg:py-20 bg-neutral-primary min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Pengajuan Permohonan</h1>
            <p class="text-body-subtle text-lg">Lengkapi data diri dan unggah berkas persyaratan sesuai ketentuan.</p>
        </div>
        
        <div class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-6 sm:p-10 lg:p-12">
            
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

            <form action="{{ url('/permohonan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Data Diri Section -->
                <div class="mb-14 relative z-10">
                    <div class="absolute right-0 top-0 text-brand opacity-5 text-[10rem] pointer-events-none select-none -z-10">
                        <i class="fa-solid fa-id-card-clip"></i>
                    </div>

                    <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-8 flex items-center gap-3 border-b border-border-default pb-4">
                        <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-regular fa-id-card"></i></span>
                        Data Diri Pemohon
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">NIK <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16" placeholder="Nomor Induk Kependudukan (16 Digit)">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Nama Lengkap & Gelar <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso, S.H., M.H.">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Tempat Lahir <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota kelahiran">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Tanggal Lahir <span class="text-fg-danger">*</span></label>
                            <input type="date" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Jenis Kelamin <span class="text-fg-danger">*</span></label>
                            <select class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="jenis_kelamin" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Organisasi Advokat <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="nama_organisasi" value="{{ old('nama_organisasi') }}" required placeholder="Ketik nama organisasi advokat">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Nomor HP/WhatsApp <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Email Aktif <span class="text-fg-danger">*</span></label>
                            <input type="email" class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="email" value="{{ old('email') }}" required placeholder="email@example.com">
                        </div>
                        <div class="md:col-span-2 group">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Alamat Lengkap <span class="text-fg-danger">*</span></label>
                            <textarea class="block w-full rounded-xl border border-border-default-medium bg-neutral-primary-soft shadow-inset text-[15px] text-heading py-3.5 px-4 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand focus:bg-white transition-all" name="alamat" rows="3" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
                        </div>
                        
                        <!-- Pas Foto Pemohon -->
                        <div class="group mt-2 flex flex-col h-full">
                            <label class="block text-[14px] font-bold text-heading mb-2 group-focus-within:text-brand transition-colors">Pas Foto Pemohon <span class="text-fg-danger">*</span></label>
                            <label for="foto" class="flex flex-col items-center justify-center w-full flex-grow border-2 border-border-default-medium border-dashed rounded-xl cursor-pointer bg-neutral-primary hover:bg-neutral-secondary-soft transition-colors hover:border-brand group-hover:bg-white p-6">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-brand mb-3 transition-transform group-hover:-translate-y-1"></i>
                                <p class="mb-1 text-[14px] text-heading font-semibold text-center leading-snug" id="foto-text" style="word-break: break-word;">
                                    <span class="text-brand">Klik unggah</span> atau seret foto
                                </p>
                                <p class="text-[12px] text-body-subtle mt-1 text-center">Format JPG/PNG (Maks. 2MB)</p>
                                <input id="foto" name="foto" type="file" class="hidden" required accept="image/jpeg, image/png, image/jpg" onchange="document.getElementById('foto-text').innerHTML = this.files[0] ? '<span class=\'text-brand\'><i class=\'fa-solid fa-image mr-1\'></i></span> ' + this.files[0].name : '<span class=\'text-brand\'>Klik unggah</span> atau seret foto'" />
                            </label>
                        </div>
                        
                        <!-- Info Cetak Fisik -->
                        <div class="flex flex-col mt-2 h-full">
                            <label class="block text-[14px] font-bold text-transparent mb-2 hidden md:block select-none">Info</label>
                            <div class="p-5 rounded-xl bg-brand/5 border border-brand/20 shadow-sm flex items-start gap-4 flex-grow">
                                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 border border-brand/20">
                                    <i class="fa-solid fa-camera-retro text-lg"></i>
                                </div>
                                <div>
                                    <h5 class="text-[14px] font-bold text-brand-strong mb-1">Cetak Fisik Diperlukan</h5>
                                    <p class="text-[13px] text-body-subtle leading-relaxed">
                                        Saat verifikasi dokumen fisik di Pengadilan, Anda <strong>wajib</strong> membawa pas foto <strong>3x4</strong> sebanyak <strong>3 lembar</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Persyaratan Section -->
                <div class="relative z-10">
                    <h4 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-8 flex items-center gap-3 border-b border-border-default pb-4">
                        <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-solid fa-file-pdf"></i></span>
                        Unggah Dokumen Persyaratan
                    </h4>
                    
                    <div class="mb-8 p-5 rounded-2xl bg-neutral-primary border border-border-default shadow-inset flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 border border-brand/20">
                            <i class="fa-solid fa-circle-info text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[14px] text-body leading-relaxed mt-1">Silakan unggah dokumen persyaratan dalam format <strong class="text-brand">PDF, JPG, atau PNG</strong>. Ukuran file maksimal adalah <strong class="text-brand">2MB per dokumen</strong>. Berkas yang tidak terbaca dapat menyebabkan penolakan verifikasi.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($persyaratan as $p)
                        <div class="bg-neutral-primary-soft rounded-2xl p-6 border border-border-default shadow-sm hover:shadow-md transition-all group flex flex-col h-full relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 text-brand opacity-5 text-[6rem] group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 pointer-events-none select-none z-0">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>

                            <label class="block text-[16px] font-bold text-heading mb-2 relative z-10">{{ $p->nama_persyaratan }} @if($p->is_required) <span class="text-fg-danger">*</span> @endif</label>
                            <p class="text-[13px] text-body-subtle mb-5 relative z-10">{{ $p->deskripsi }}</p>
                            
                            <div class="mt-auto relative z-10 w-full">
                                <label for="dokumen_{{ $p->id }}" class="flex items-center justify-center w-full px-4 py-3 border-2 border-border-default-medium border-dashed rounded-xl cursor-pointer bg-neutral-primary hover:bg-neutral-secondary-soft transition-colors hover:border-brand group-hover:bg-white">
                                    <div class="flex items-center justify-center gap-3 w-full overflow-hidden">
                                        <i class="fa-solid fa-upload text-brand/70 text-lg flex-shrink-0"></i>
                                        <span id="dokumen-name-{{ $p->id }}" class="text-[13px] font-semibold text-body truncate w-full text-center group-hover:text-brand transition-colors">Pilih File...</span>
                                    </div>
                                    <input id="dokumen_{{ $p->id }}" type="file" class="hidden" name="dokumen[{{ $p->id }}]" {{ $p->is_required ? 'required' : '' }} accept=".pdf, image/jpeg, image/png, image/jpg" onchange="document.getElementById('dokumen-name-{{ $p->id }}').textContent = this.files[0] ? this.files[0].name : 'Pilih File...'">
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-12 text-center pt-8 border-t border-border-default">
                    <button type="submit" class="inline-flex justify-center items-center px-10 py-4 rounded-full text-[16px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-1 active:shadow-inset active:translate-y-0 transition-all duration-300 border border-brand-softer">
                        <i class="fa-solid fa-paper-plane mr-3"></i> Submit Permohonan
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>
@endsection
