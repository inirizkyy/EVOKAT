@extends('layouts.admin')
@section('title', isset($persyaratan) ? 'Edit Syarat' : 'Tambah Syarat')

@section('actions')
<a href="{{ route('admin.persyaratan.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Form Persyaratan</h6>
            </div>
            <div class="p-6">
                <form action="{{ isset($persyaratan) ? route('admin.persyaratan.update', $persyaratan->id) : route('admin.persyaratan.store') }}" method="POST">
                    @csrf
                    @if(isset($persyaratan)) @method('PUT') @endif
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Nama Persyaratan <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="nama_persyaratan" value="{{ old('nama_persyaratan', $persyaratan->nama_persyaratan ?? '') }}" required>
                        @error('nama_persyaratan') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Deskripsi Tambahan</label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="deskripsi" rows="3">{{ old('deskripsi', $persyaratan->deskripsi ?? '') }}</textarea>
                        @error('deskripsi') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Sifat Dokumen <span class="text-fg-danger">*</span></label>
                        <select class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="is_required" required>
                            <option value="1" {{ old('is_required', $persyaratan->is_required ?? 1) == 1 ? 'selected' : '' }}>Wajib (Required)</option>
                            <option value="0" {{ old('is_required', $persyaratan->is_required ?? 1) == 0 ? 'selected' : '' }}>Opsional (Optional)</option>
                        </select>
                        @error('is_required') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-border-default">
                        <button type="submit" class="inline-flex items-center px-6 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Persyaratan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
