@extends('layouts.admin')
@section('title', 'Dashboard Pemeriksa')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: Total Data Lengkap -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-info p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-info uppercase tracking-wider mb-1">Total Data Lengkap</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countAll }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-info-soft flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-2xl text-fg-info"></i>
                </div>
            </div>
        </div>

        <!-- Card: Menunggu Persetujuan -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-warning p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-warning uppercase tracking-wider mb-1">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countPending }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-warning-soft flex items-center justify-center">
                    <i class="fa-solid fa-clock text-2xl text-fg-warning"></i>
                </div>
            </div>
        </div>

        <!-- Card: Telah Disetujui -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-success p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-success uppercase tracking-wider mb-1">Telah Disetujui</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countApproved }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-success-soft flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-2xl text-fg-success"></i>
                </div>
            </div>
        </div>
    </div>


    @if(session('info'))
        <div class="p-4 rounded-xl bg-info-soft border border-border-info-subtle text-fg-info text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default overflow-hidden">
        <!-- Header & Filters -->
        <div class="p-6 border-b border-border-default flex flex-col md:flex-row md:items-center justify-between gap-4 bg-neutral-secondary-soft">
            <h5 class="font-bold text-heading m-0 flex items-center gap-2">
                <i class="fa-solid fa-address-book text-brand"></i> Data Buku Registrasi Advokat (Selesai Verifikasi)
            </h5>
            
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('pemeriksa.dashboard', ['status' => 'all', 'search_name' => request('search_name')]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold transition-all border {{ $status === 'all' ? 'bg-brand text-white border-brand' : 'bg-white text-body-subtle border-border-default hover:bg-neutral-primary-soft' }}">
                    Semua
                </a>
                <a href="{{ route('pemeriksa.dashboard', ['status' => 'pending', 'search_name' => request('search_name')]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold transition-all border {{ $status === 'pending' ? 'bg-brand text-white border-brand' : 'bg-white text-body-subtle border-border-default hover:bg-neutral-primary-soft' }}">
                    Menunggu Persetujuan
                </a>
                <a href="{{ route('pemeriksa.dashboard', ['status' => 'disetujui', 'search_name' => request('search_name')]) }}" 
                   class="px-4 py-2 rounded-full text-xs font-bold transition-all border {{ $status === 'disetujui' ? 'bg-brand text-white border-brand' : 'bg-white text-body-subtle border-border-default hover:bg-neutral-primary-soft' }}">
                    Telah Disetujui
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="p-6 border-b border-border-default/50 bg-white">
            <form method="GET" action="{{ route('pemeriksa.dashboard') }}" class="flex gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-body-subtle">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search_name" value="{{ request('search_name') }}" placeholder="Cari nama advokat..." 
                           class="block w-full pl-10 pr-4 py-2 rounded-base border border-border-default-medium bg-white text-sm focus:outline-none focus:border-brand transition-all">
                </div>
                <button type="submit" class="px-5 py-2 bg-brand text-white text-sm font-bold rounded-base hover:bg-brand-strong transition-all">
                    Cari
                </button>
                @if(request('search_name'))
                    <a href="{{ route('pemeriksa.dashboard', ['status' => $status]) }}" class="px-5 py-2 bg-neutral-secondary-soft text-heading border border-border-default text-sm font-bold rounded-base hover:bg-neutral-secondary transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft/50 border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[3%]">No</th>
                        <th class="px-6 py-3 font-medium w-[8%]">Foto</th>
                        <th class="px-6 py-3 font-medium">Nama &amp; NIK</th>
                        <th class="px-6 py-3 font-medium">Organisasi</th>
                        <th class="px-6 py-3 font-medium">SK Advokat</th>
                        <th class="px-6 py-3 font-medium">BAS &amp; Sumpah</th>
                        <th class="px-6 py-3 font-medium text-center">Status Pemeriksa</th>
                        <th class="px-6 py-3 font-medium text-center w-[12%]">Aksi</th>
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
                                <div class="text-[13px] text-body-subtle mt-1 font-mono">NIK: {{ $item->pemohon->nik }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-heading">{{ $item->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                            </td>
                            <!-- SK Advokat -->
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="font-medium text-heading">No: {{ $item->permohonan->nomor_sk ?? $item->pemohon->nomor_sk ?? '-' }}</div>
                                <div class="text-[12px] text-body-subtle mt-1">Tgl: {{ ($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-heading font-medium text-[14px]">BAS: <span class="font-mono font-semibold text-brand">{{ $item->nomor_bas }}</span></div>
                                <div class="text-[14px] text-heading font-medium mt-0.5"><i class="fa-solid fa-calendar mr-1 text-brand"></i>Sumpah: {{ \Carbon\Carbon::parse($item->tanggal_disumpah)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status_pemeriksa === 'Disetujui')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">
                                        <i class="fa-solid fa-circle-check mr-1.5"></i>Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                                        <i class="fa-solid fa-clock mr-1.5"></i>Menunggu Approval
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('pemeriksa.buku-registrasi.show-member', $item->id) }}" title="Lihat Detail" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default text-xs font-semibold">
                                        <i class="fa-solid fa-eye mr-1"></i> Detail
                                    </a>
                                    
                                    @if($item->status_pemeriksa !== 'Disetujui')
                                        <form action="{{ route('pemeriksa.buku-registrasi.approve', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-success text-white shadow-sm hover:bg-success-strong active:shadow-inset transition-all text-xs font-semibold">
                                                <i class="fa-solid fa-check mr-1"></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('pemeriksa.buku-registrasi.unlock', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membuka kunci data ini? Admin akan dapat mengedit data kembali.');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-danger-soft border border-border-danger-subtle text-fg-danger-strong hover:bg-danger hover:text-white transition-all text-xs font-semibold shadow-sm">
                                                <i class="fa-solid fa-lock-open mr-1"></i> Unlock
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-body-subtle italic">Tidak ada data registrasi advokat yang siap diperiksa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrasi->hasPages())
        <div class="p-4 border-t border-border-default">
            {{ $registrasi->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
