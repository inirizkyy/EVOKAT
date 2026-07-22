@extends('layouts.admin')
@section('title', 'Lengkapi Data Sumpah Advokat')

@section('actions')
<a href="{{ route('admin.buku-registrasi.show', $reg->permohonan_id) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
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

    @if($reg->status_pemeriksa === 'Disetujui')
    <div class="mb-6 p-4 rounded-xl bg-info-soft border border-border-info-subtle text-fg-info text-[13px] flex items-center gap-2 font-medium">
        <i class="fa-solid fa-lock"></i>
        <span>Data ini telah disetujui oleh Pemeriksa dan telah dikunci. Anda tidak dapat mengubah data ini lagi.</span>
    </div>
    @endif

    <form action="{{ route('admin.buku-registrasi.update', $reg->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nomor BAS -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Nomor Berita Acara Sumpah (BAS) <span class="text-fg-danger">*</span></label>
            <input type="text" name="nomor_bas" value="{{ old('nomor_bas', $reg->nomor_bas) }}" required placeholder="Contoh: W9.U1/123/HK.06.2/7/2026"
                   @disabled($reg->status_pemeriksa === 'Disetujui')
                   class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed">
        </div>

        <!-- Tanggal Disumpah -->
        <div>
            <label class="block text-[14px] font-semibold text-heading mb-2">Tanggal Disumpah <span class="text-fg-danger">*</span></label>
            <input type="date" name="tanggal_disumpah" value="{{ old('tanggal_disumpah', $reg->tanggal_disumpah ? \Carbon\Carbon::parse($reg->tanggal_disumpah)->toDateString() : '') }}" required
                   @disabled($reg->status_pemeriksa === 'Disetujui')
                   class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed">
        </div>

        <!-- Pejabat yang Memimpin Sumpah Advokat -->
        <div x-data="{ 
            selectedLeader: '{{ old('ketua_pengadilan_tinggi', $reg->ketua_pengadilan_tinggi) }}',
            customLeader: '',
            init() {
                const knownLeaders = @json($leaders->pluck('name'));
                if (this.selectedLeader !== '' && !knownLeaders.includes(this.selectedLeader)) {
                    this.customLeader = this.selectedLeader;
                    this.selectedLeader = 'custom_leader';
                }
                this.updateHidden();
            },
            updateHidden() {
                document.getElementById('ketua_pengadilan_tinggi').value = this.selectedLeader === 'custom_leader' ? this.customLeader : this.selectedLeader;
            }
        }">
            <label class="block text-[14px] font-semibold text-heading mb-2">Nama yang Memimpin Sumpah Advokat <span class="text-fg-danger">*</span></label>
            <select @change="selectedLeader = $event.target.value; updateHidden();" 
                    @disabled($reg->status_pemeriksa === 'Disetujui')
                    class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed" required>
                <option value="">Pilih Pejabat</option>
                @foreach($leaders as $leader)
                    <option value="{{ $leader->name }}" :selected="selectedLeader === '{{ $leader->name }}'">{{ $leader->name }}</option>
                @endforeach
                <option value="custom_leader" :selected="selectedLeader === 'custom_leader'">+ Ajukan Nama Baru</option>
            </select>
            <div x-show="selectedLeader === 'custom_leader'" class="mt-3">
                <input type="text" x-model="customLeader" @input="updateHidden()" placeholder="Masukkan nama pejabat..."
                       @disabled($reg->status_pemeriksa === 'Disetujui')
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed">
            </div>
            <input type="hidden" name="ketua_pengadilan_tinggi" id="ketua_pengadilan_tinggi" value="">
        </div>

        <!-- Nama Saksi 1 & 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-[14px] font-semibold text-heading mb-2">Nama Saksi 1 <span class="text-fg-danger">*</span></label>
                <input type="text" name="saksi_1" value="{{ old('saksi_1', $saksi_1) }}" required placeholder="Nama Saksi 1 beserta gelar..."
                       @disabled($reg->status_pemeriksa === 'Disetujui')
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed">
            </div>
            <div>
                <label class="block text-[14px] font-semibold text-heading mb-2">Nama Saksi 2 <span class="text-fg-danger">*</span></label>
                <input type="text" name="saksi_2" value="{{ old('saksi_2', $saksi_2) }}" required placeholder="Nama Saksi 2 beserta gelar..."
                       @disabled($reg->status_pemeriksa === 'Disetujui')
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] py-3 px-4 focus:outline-none focus:border-brand transition-all disabled:bg-neutral-primary-soft disabled:text-body-subtle disabled:cursor-not-allowed">
            </div>
        </div>
        <p class="text-[12px] text-body-subtle mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i> Tuliskan nama saksi lengkap beserta gelarnya (saksi merupakan Pejabat/Pegawai Pengadilan).</p>

        <!-- Tombol Aksi -->
        <div class="pt-4 border-t border-border-default flex justify-end gap-2">
            <a href="{{ route('admin.buku-registrasi.show', $reg->permohonan_id) }}" class="inline-flex justify-center items-center px-6 py-3 rounded-full text-[14px] font-bold bg-neutral-secondary-soft border border-border-default text-heading hover:bg-neutral-secondary transition-all">
                @if($reg->status_pemeriksa === 'Disetujui') Kembali @else Batal @endif
            </a>
            @if($reg->status_pemeriksa === 'Disetujui')
                <button type="button" disabled class="inline-flex justify-center items-center px-8 py-3 rounded-full text-[14px] font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed">
                    Terkunci <i class="fa-solid fa-lock ml-2 text-brand"></i>
                </button>
            @else
                <button type="submit" class="inline-flex justify-center items-center px-8 py-3 rounded-full text-[14px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:bg-brand-strong transition-all">
                    Simpan Perubahan <i class="fa-solid fa-save ml-2"></i>
                </button>
            @endif
        </div>
    </form>
</div>
@endsection
