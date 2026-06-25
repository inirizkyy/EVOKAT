@extends('layouts.admin')
@section('title', isset($faq) ? 'Edit FAQ' : 'Tambah FAQ')

@section('actions')
<a href="{{ route('admin.faq.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Form FAQ</h6>
            </div>
            <div class="p-6">
                <form action="{{ isset($faq) ? route('admin.faq.update', $faq->id) : route('admin.faq.store') }}" method="POST">
                    @csrf
                    @if(isset($faq)) @method('PUT') @endif
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Pertanyaan <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="pertanyaan" value="{{ old('pertanyaan', $faq->pertanyaan ?? '') }}" required>
                        @error('pertanyaan') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Jawaban <span class="text-fg-danger">*</span></label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="jawaban" rows="6" required>{{ old('jawaban', $faq->jawaban ?? '') }}</textarea>
                        @error('jawaban') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-border-default">
                        <button type="submit" class="inline-flex items-center px-6 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                            <i class="fa-solid fa-save mr-2"></i> Simpan FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
