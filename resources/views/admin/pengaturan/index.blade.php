@extends('layouts.admin')
@section('title', 'Pengaturan Website')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Form Pengaturan</h6>
            </div>
            <div class="p-6">
                @if(!$pengaturan)
                    <div class="p-4 mb-6 rounded-base border border-border-warning-subtle bg-warning-soft text-fg-warning flex items-center shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-3"></i>
                        <p class="text-[14px] m-0">Data pengaturan belum tersedia (Seeder belum dijalankan).</p>
                    </div>
                @else
                <form action="{{ route('admin.pengaturan.update', $pengaturan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="text-lg font-bold text-heading mb-4 pb-2 border-b border-border-default">Informasi Umum</h5>
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Nama Instansi <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="nama_instansi" value="{{ old('nama_instansi', $pengaturan->nama_instansi) }}" required>
                        @error('nama_instansi') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Deskripsi Singkat Web <span class="text-fg-danger">*</span></label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="deskripsi_singkat" rows="3" required>{{ old('deskripsi_singkat', $pengaturan->deskripsi_singkat) }}</textarea>
                        @error('deskripsi_singkat') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <h5 class="text-lg font-bold text-heading mb-4 mt-8 pb-2 border-b border-border-default">Kontak & Alamat</h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-[14px] font-medium text-heading mb-2">Telepon <span class="text-fg-danger">*</span></label>
                            <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="telepon" value="{{ old('telepon', $pengaturan->telepon) }}" required>
                            @error('telepon') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-[14px] font-medium text-heading mb-2">Email <span class="text-fg-danger">*</span></label>
                            <input type="email" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="email" value="{{ old('email', $pengaturan->email) }}" required>
                            @error('email') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Alamat Lengkap <span class="text-fg-danger">*</span></label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="alamat" rows="2" required>{{ old('alamat', $pengaturan->alamat) }}</textarea>
                        @error('alamat') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Google Maps Embed (Iframe)</label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="maps_embed" rows="4">{{ old('maps_embed', $pengaturan->maps_embed) }}</textarea>
                        <div class="text-xs text-body-subtle mt-1">Paste kode &lt;iframe&gt; dari Google Maps.</div>
                        @error('maps_embed') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-border-default">
                        <button type="submit" class="inline-flex items-center px-6 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                            <i class="fa-solid fa-save mr-2"></i> Update Pengaturan
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
