@extends('layouts.admin')
@section('title', isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal')

@section('actions')
<a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Form Jadwal</h6>
            </div>
            <div class="p-6">
                <form action="{{ isset($jadwal) ? route('admin.jadwal.update', $jadwal->id) : route('admin.jadwal.store') }}" method="POST">
                    @csrf
                    @if(isset($jadwal)) @method('PUT') @endif
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">ID Permohonan <span class="text-fg-danger">*</span></label>
                        <select name="permohonan_id" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">-- Pilih Permohonan (Hanya yang berstatus Disetujui) --</option>
                            @foreach(\App\Models\Permohonan::whereIn('status', ['Disetujui', 'Dijadwalkan Sumpah'])->with('pemohon')->get() as $p)
                                <option value="{{ $p->id }}" {{ old('permohonan_id', $jadwal->permohonan_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nomor_permohonan }} - {{ $p->pemohon->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-xs text-body-subtle mt-1">Catatan: Pastikan permohonan sudah disetujui verifikator sebelum dijadwalkan.</div>
                        @error('permohonan_id') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-[14px] font-medium text-heading mb-2">Tanggal Pelaksanaan <span class="text-fg-danger">*</span></label>
                            <input type="date" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal ?? '') }}" required>
                            @error('tanggal') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-[14px] font-medium text-heading mb-2">Jam Pelaksanaan (WIB) <span class="text-fg-danger">*</span></label>
                            <input type="time" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="jam" value="{{ old('jam', isset($jadwal) ? \Carbon\Carbon::parse($jadwal->jam)->format('H:i') : '') }}" required>
                            @error('jam') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-[14px] font-medium text-heading mb-2">Lokasi <span class="text-fg-danger">*</span></label>
                        <input type="text" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi ?? 'Ruang Sidang Utama Pengadilan Tinggi Tanjungkarang') }}" required>
                        @error('lokasi') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Keterangan Tambahan</label>
                        <textarea class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" name="keterangan" rows="3">{{ old('keterangan', $jadwal->keterangan ?? '') }}</textarea>
                        <div class="text-xs text-body-subtle mt-1">Instruksi seperti pakaian (toga) dsb.</div>
                        @error('keterangan') <div class="text-xs text-fg-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-border-default">
                        <button type="submit" class="inline-flex items-center px-6 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                            <i class="fa-solid fa-save mr-2"></i> Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
