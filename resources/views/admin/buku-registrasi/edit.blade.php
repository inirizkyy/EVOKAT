@extends('layouts.admin')
@section('title', 'Lengkapi Data Sumpah Advokat')

@section('actions')
<a href="{{ route('admin.buku-registrasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-border-default max-w-2xl mx-auto p-6 sm:p-8">
    <div class="mb-6 pb-4 border-b border-border-default flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-lg"><i class="fa-solid fa-graduation-cap"></i></div>
        <div>
            <h5 class="font-bold text-heading m-0">Lengkapi Data BAS</h5>
            <p class="text-xs text-body-subtle m-0">Untuk Pemohon: <span class="font-bold text-heading">{{ $reg->pemohon->nama_lengkap }}</span></p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">
        <ul class="list-disc list-inside text-[13px] space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.buku-registrasi.update', $reg->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nomor BAS -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Nomor Berita Acara Sumpah (BAS) <span class="text-fg-danger">*</span></label>
            <input type="text" name="nomor_bas" value="{{ old('nomor_bas', $reg->nomor_bas) }}" required placeholder="Contoh: W9.U1/123/HK.06.2/7/2026"
                   class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all">
        </div>

        <!-- Tanggal Disumpah -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Tanggal Disumpah <span class="text-fg-danger">*</span></label>
            <input type="date" name="tanggal_disumpah" value="{{ old('tanggal_disumpah', $reg->tanggal_disumpah ? \Carbon\Carbon::parse($reg->tanggal_disumpah)->toDateString() : '') }}" required
                   class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all">
        </div>

        <!-- Ketua Pengadilan Tinggi -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Nama Ketua Pengadilan Tinggi yang Menyumpah <span class="text-fg-danger">*</span></label>
            <input type="text" name="ketua_pengadilan_tinggi" value="{{ old('ketua_pengadilan_tinggi', $reg->ketua_pengadilan_tinggi) }}" required placeholder="Contoh: Dr. H. Sunaryo, S.H., M.H."
                   class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all">
        </div>

        <!-- Nama Saksi -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Nama Saksi-Saksi <span class="text-fg-danger">*</span></label>
            <textarea name="saksi" rows="4" required placeholder="Sebutkan nama saksi 1 & saksi 2..."
                      class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all">{{ old('saksi', $reg->saksi) }}</textarea>
            <p class="text-[12px] text-body-subtle mt-1.5">Tuliskan nama saksi lengkap beserta gelarnya (biasanya 2 orang saksi dari Pejabat Pengadilan).</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-4 border-t border-border-default flex justify-end gap-2">
            <a href="{{ route('admin.buku-registrasi.index') }}" class="inline-flex justify-center items-center px-6 py-3 rounded-full text-[14px] font-bold bg-neutral-secondary-soft border border-border-default text-heading hover:bg-neutral-secondary transition-all">
                Batal
            </a>
            <button type="submit" class="inline-flex justify-center items-center px-8 py-3 rounded-full text-[14px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:bg-brand-strong transition-all">
                Simpan Perubahan <i class="fa-solid fa-save ml-2"></i>
            </button>
        </div>
    </form>
</div>
@endsection
