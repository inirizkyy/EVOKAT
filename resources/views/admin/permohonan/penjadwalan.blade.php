@extends('layouts.admin')
@section('title', 'Penjadwalan Berkas Fisik')

@section('actions')
<a href="{{ route('admin.permohonan.show', $permohonan->id) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

    <!-- Kolom Kiri: Form Verifikasi (2 Kolom) -->
    <div class="lg:col-span-2 space-y-8">
        @if(session('success'))
        <div class="p-4 rounded-xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3">
            <i class="fa-solid fa-circle-check mt-1"></i>
            <p class="text-[14px]">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong flex items-start gap-3">
            <i class="fa-solid fa-circle-xmark mt-1"></i>
            <p class="text-[14px]">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Form Penjadwalan Berkas Fisik -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col"
             x-data="{
                hariVerifikasi: '',
                setHariVerifikasi(val) {
                    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    this.hariVerifikasi = val ? days[new Date(val).getDay()] : '';
                }
             }">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Penjadwalan Berkas Fisik</h6>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-2">Status Baru yang Diajukan</label>
                        <div class="px-4 py-3 bg-brand/5 border border-brand/20 rounded-base text-brand font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-arrow-right-long text-red-500"></i> Menentukan Jadwal Verifikasi
                        </div>
                        <input type="hidden" name="status" value="Menentukan Jadwal Verifikasi">
                    </div>

                    <div class="p-5 rounded-xl bg-warning-soft border border-border-warning-subtle space-y-4">
                        <p class="text-[13px] font-semibold text-fg-warning flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-orange-500"></i>
                            Tentukan jadwal penyerahan berkas fisik
                        </p>
                        <div>
                            <label class="block text-[13px] font-medium text-heading mb-1.5">Tanggal <span class="text-fg-danger">*</span></label>
                            <input type="date" name="tanggal_verifikasi_fisik" min="{{ now()->toDateString() }}"
                                   @change="setHariVerifikasi($event.target.value)" required
                                   class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                            <div x-show="hariVerifikasi" class="mt-2.5 flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-fg-warning text-sm"></i>
                                <span class="text-[13px] font-semibold text-fg-warning">Hari: <span x-text="hariVerifikasi" class="font-bold"></span></span>
                            </div>
                            <input type="hidden" name="hari_verifikasi_fisik" :value="hariVerifikasi">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan Keterangan</label>
                        <textarea name="catatan" rows="4" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2.5 px-3.5 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" placeholder="Catatan hasil verifikasi atau keterangan jadwal..."></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 rounded-base text-[15px] font-bold bg-brand text-white shadow-sm hover:shadow-md hover:opacity-95 active:shadow-inset transition-all border border-brand-softer">
                        <i class="fas fa-save mr-2"></i> Konfirmasi Status Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Riwayat Status Permohonan (1 Kolom, Scrollable) -->
    <div class="lg:col-span-1">
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col lg:h-[530px] h-[400px]">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base flex-shrink-0">
                <h6 class="font-bold text-heading">Riwayat Status Permohonan</h6>
            </div>
            <div class="p-6 overflow-y-auto flex-grow custom-scrollbar">
                <div class="relative border-l border-brand ml-2 space-y-6 pb-2">
                    @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $riwayat)
                    <div class="relative pl-6">
                        <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-brand -translate-x-1/2"></div>
                        <div class="text-[13px] text-heading font-bold leading-snug">{{ $riwayat->status_baru }}</div>
                        <div class="text-[11px] text-body-subtle mt-0.5">{{ \Carbon\Carbon::parse($riwayat->changed_at)->translatedFormat('d M Y - H:i') }}</div>
                        @if($riwayat->keterangan)
                            <div class="text-[12px] text-body mt-2 bg-white p-3 rounded-base border border-border-default shadow-sm font-medium whitespace-normal break-words">
                                {{ $riwayat->keterangan }}
                            </div>
                        @endif
                    </div>
                    @empty
                    <div class="pl-4 text-body-subtle text-xs italic">Belum ada riwayat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
