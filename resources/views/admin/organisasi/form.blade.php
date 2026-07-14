@extends('layouts.admin')
@section('title', isset($organization) ? 'Edit Organisasi' : 'Tambah Organisasi')

@section('actions')
<a href="{{ route('admin.organisasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
</a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default overflow-hidden">
        <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft">
            <h6 class="m-0 font-bold text-heading text-lg">
                {{ isset($organization) ? 'Edit Data Organisasi' : 'Form Tambah Organisasi' }}
            </h6>
        </div>
        <div class="p-6">
            <form action="{{ isset($organization) ? route('admin.organisasi.update', $organization->id) : route('admin.organisasi.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($organization))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-[14px] font-medium text-heading mb-2">Nama Organisasi <span class="text-fg-danger">*</span></label>
                    <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi', $organization->nama_organisasi ?? '') }}" required
                           class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all"
                           placeholder="Contoh: Perhimpunan Advokat Indonesia">
                    @error('nama_organisasi')
                        <div class="text-xs text-fg-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-[14px] font-medium text-heading mb-2">Singkatan</label>
                    <input type="text" name="singkatan" value="{{ old('singkatan', $organization->singkatan ?? '') }}"
                           class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all"
                           placeholder="Contoh: PERADI">
                    @error('singkatan')
                        <div class="text-xs text-fg-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-[14px] font-medium text-heading mb-2">Status <span class="text-fg-danger">*</span></label>
                    <select name="status" required
                            class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                        <option value="Aktif" {{ old('status', $organization->status ?? 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $organization->status ?? 'Aktif') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="Menunggu Persetujuan" {{ old('status', $organization->status ?? 'Aktif') === 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    </select>
                    @error('status')
                        <div class="text-xs text-fg-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 rounded-base text-[15px] font-bold bg-brand text-white shadow-sm hover:shadow-md hover:opacity-95 active:shadow-inset transition-all border border-brand-softer">
                    <i class="fas fa-save mr-2"></i> Simpan Organisasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
