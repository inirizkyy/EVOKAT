@extends('layouts.admin')
@section('title', 'Buku Registrasi Advokat')

@section('content')
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default p-6">
        <form method="GET" action="{{ route($role . '.buku-registrasi.index') }}" class="flex gap-2">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-body-subtle">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search_name" value="{{ request('search_name') }}" placeholder="Cari nama advokat..." 
                       class="block w-full pl-10 pr-4 py-2.5 rounded-base border border-border-default-medium bg-white text-sm focus:outline-none focus:border-brand transition-all">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-brand text-white text-sm font-bold rounded-base hover:bg-brand-strong transition-all">
                Cari
            </button>
            @if(request('search_name'))
                <a href="{{ route($role . '.buku-registrasi.index') }}" class="px-5 py-2.5 bg-neutral-secondary-soft text-heading border border-border-default text-sm font-bold rounded-base hover:bg-neutral-secondary transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default overflow-hidden">
        <div class="p-6 border-b border-border-default bg-neutral-secondary-soft">
            <h5 class="font-bold text-heading m-0 flex items-center gap-2">
                <i class="fa-solid fa-address-book text-brand"></i> Data Buku Registrasi Advokat
            </h5>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft/50 border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-4 font-medium w-[5%]">No</th>
                        <th class="px-6 py-4 font-medium w-[8%]">Foto</th>
                        <th class="px-6 py-4 font-medium">Nama &amp; NIK</th>
                        <th class="px-6 py-4 font-medium">Organisasi</th>
                        <th class="px-6 py-4 font-medium">SK Advokat</th>
                        <th class="px-6 py-4 font-medium">BAS &amp; Sumpah</th>
                        <th class="px-6 py-4 font-medium text-center w-[12%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-default bg-white">
                    @forelse($registrasi as $index => $item)
                        <tr class="hover:bg-neutral-secondary-soft/35 transition-colors">
                            <td class="px-6 py-4">{{ $registrasi->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                @if($item->pemohon && $item->pemohon->foto)
                                    <img src="{{ asset('storage/' . $item->pemohon->foto) }}" alt="Foto Pemohon" class="w-12 h-12 object-cover rounded shadow-sm border border-border-default" style="width: 48px !important; height: 48px !important; min-width: 48px !important; min-height: 48px !important; max-width: 48px !important; max-height: 48px !important; object-fit: cover !important; aspect-ratio: 1/1 !important;">
                                @else
                                    <div class="w-12 h-12 bg-neutral-secondary-soft rounded border border-border-default flex items-center justify-center text-body-subtle">
                                        <i class="fa-solid fa-user text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-heading text-[15px]">{{ $item->pemohon->nama_lengkap }}</div>
                                <div class="text-[13px] text-body-subtle mt-1 font-mono font-semibold">NIK: {{ $item->pemohon->nik }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-heading">{{ $item->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-medium text-heading">No: {{ $item->permohonan->nomor_sk ?? $item->pemohon->nomor_sk ?? '-' }}</div>
                                <div class="text-[12px] text-body-subtle mt-1">Tgl: {{ ($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-heading font-medium text-[14px]">BAS: <span class="font-mono font-semibold text-brand">{{ $item->nomor_bas }}</span></div>
                                <div class="text-[14px] text-heading font-medium mt-0.5"><i class="fa-solid fa-calendar mr-1 text-brand"></i>Sumpah: {{ \Carbon\Carbon::parse($item->tanggal_disumpah)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route($role . '.buku-registrasi.show-member', $item->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default text-xs font-bold">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-body-subtle italic">Tidak ada data registrasi advokat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrasi->hasPages())
        <div class="p-4 border-t border-border-default">
            {{ $registrasi->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
