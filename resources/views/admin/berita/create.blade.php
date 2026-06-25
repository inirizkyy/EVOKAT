@extends('layouts.admin')
@section('title', 'Tambah Berita')

@section('actions')
<a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
    <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
        <h6 class="m-0 font-bold text-heading">Form Berita</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-5">
                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Judul Berita <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="judul" value="{{ old('judul') }}" required>
                        @error('judul') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Isi Berita <span class="text-fg-danger">*</span></label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="isi" rows="10" required>{{ old('isi') }}</textarea>
                        @error('isi') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="space-y-5">
                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Tanggal Publikasi</label>
                        <input type="date" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}">
                        @error('published_at') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Thumbnail</label>
                        <input type="file" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all file:mr-4 file:py-2 file:px-4 file:rounded-base file:border-0 file:text-sm file:font-semibold file:bg-neutral-secondary-soft file:text-body hover:file:bg-neutral-tertiary-medium cursor-pointer" name="thumbnail" accept="image/jpeg, image/png, image/jpg">
                        <div class="text-xs text-body-subtle mt-1">Format JPG/PNG. Maksimal 2MB.</div>
                        @error('thumbnail') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="pt-4 border-t border-border-default">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Berita
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
