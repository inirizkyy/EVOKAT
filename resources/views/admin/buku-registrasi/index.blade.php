@extends('layouts.admin')
@section('title', 'Buku Registrasi Advokat')

@section('actions')
<div class="flex flex-wrap gap-2">
    <a href="{{ route('admin.buku-registrasi.export-excel', array_merge(['status' => $status], request()->all())) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-success shadow-sm hover:shadow-md hover:text-fg-success-strong active:shadow-inset transition-all border border-success">
        <i class="fa-solid fa-file-excel mr-2"></i> Ekspor Excel
    </a>
    <a href="{{ route('admin.buku-registrasi.export-pdf', array_merge(['status' => $status], request()->all())) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md hover:text-fg-danger-strong active:shadow-inset transition-all border border-danger">
        <i class="fa-solid fa-file-pdf mr-2"></i> Ekspor PDF
    </a>
</div>
@endsection

@section('content')
<!-- Pencarian & Filter Card -->
<div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 p-6">
    <form action="{{ route('admin.buku-registrasi.index') }}" method="GET" class="space-y-4">
        <input type="hidden" name="status" value="{{ $status }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Cari Nama -->
            <div>
                <label class="block text-[13px] font-medium text-heading mb-1.5">Nama Pemohon</label>
                <input type="text" name="search_name" value="{{ request('search_name') }}" placeholder="Cari nama lengkap..."
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[13px] py-2 px-3 focus:outline-none focus:border-brand transition-all">
            </div>

            <!-- Cari Organisasi -->
            <div>
                <label class="block text-[13px] font-medium text-heading mb-1.5">Organisasi Advokat</label>
                <select name="search_organisasi" class="block w-full rounded-base border border-border-default-medium bg-white text-[13px] py-2 px-3 focus:outline-none focus:border-brand transition-all">
                    <option value="">-- Semua Organisasi --</option>
                    @foreach(\App\Models\Organization::all() as $org)
                        <option value="{{ $org->nama_organisasi }}" {{ request('search_organisasi') == $org->nama_organisasi ? 'selected' : '' }}>
                            {{ $org->nama_organisasi }} @if($org->status === 'Menunggu Persetujuan') (Diajukan) @endif
                        </option>
                    @endforeach
                    <option value="+ Diajukan" {{ request('search_organisasi') == '+ Diajukan' ? 'selected' : '' }}>+ Diajukan (Menunggu Persetujuan)</option>
                </select>
            </div>

            <!-- Cari SK -->
            <div>
                <label class="block text-[13px] font-medium text-heading mb-1.5">Nomor SK Advokat</label>
                <input type="text" name="search_sk" value="{{ request('search_sk') }}" placeholder="Cari nomor SK..."
                       class="block w-full rounded-base border border-border-default-medium bg-white text-[13px] py-2 px-3 focus:outline-none focus:border-brand transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Filter Tanggal SK -->
            <div class="p-3 rounded-lg bg-neutral-secondary-soft border border-border-default">
                <span class="block text-[12px] font-bold text-brand uppercase tracking-wider mb-2">Filter Tanggal SK Advokat</span>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="filter_tanggal_sk_start" value="{{ request('filter_tanggal_sk_start') }}"
                           class="block w-full rounded-base border border-border-default-medium bg-white text-[12px] py-1.5 px-2 focus:outline-none focus:border-brand transition-all">
                    <input type="date" name="filter_tanggal_sk_end" value="{{ request('filter_tanggal_sk_end') }}"
                           class="block w-full rounded-base border border-border-default-medium bg-white text-[12px] py-1.5 px-2 focus:outline-none focus:border-brand transition-all">
                </div>
            </div>

            <!-- Filter Tanggal Sumpah -->
            <div class="p-3 rounded-lg bg-neutral-secondary-soft border border-border-default">
                <span class="block text-[12px] font-bold text-brand uppercase tracking-wider mb-2">Filter Tanggal Sidang Sumpah</span>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="filter_tanggal_sumpah_start" value="{{ request('filter_tanggal_sumpah_start') }}"
                           class="block w-full rounded-base border border-border-default-medium bg-white text-[12px] py-1.5 px-2 focus:outline-none focus:border-brand transition-all">
                    <input type="date" name="filter_tanggal_sumpah_end" value="{{ request('filter_tanggal_sumpah_end') }}"
                           class="block w-full rounded-base border border-border-default-medium bg-white text-[12px] py-1.5 px-2 focus:outline-none focus:border-brand transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2 border-t border-border-default">
            <a href="{{ route('admin.buku-registrasi.index', ['status' => $status]) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[13px] font-medium bg-neutral-secondary-soft border border-border-default text-heading hover:bg-neutral-secondary transition-all">
                Reset Filter
            </a>
            <button type="submit" class="inline-flex items-center px-5 py-2 rounded-base text-[13px] font-medium bg-brand text-white shadow-sm hover:bg-brand-strong transition-all">
                <i class="fa-solid fa-filter mr-2"></i> Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Tabs Navigation -->
<div class="flex border-b border-border-default mb-6 gap-2">
    <a href="{{ route('admin.buku-registrasi.index', array_merge(request()->except('page'), ['status' => 'belum_lengkap'])) }}"
       class="px-5 py-3 border-b-2 font-semibold text-[14px] transition-all flex items-center gap-2 {{ $status === 'belum_lengkap' ? 'border-brand text-brand bg-white/40 rounded-t-lg shadow-sm' : 'border-transparent text-body-subtle hover:text-heading hover:border-border-default-strong' }}">
        <i class="fa-solid fa-circle-minus"></i>
        <span>Belum Lengkap</span>
        <span class="px-2 py-0.5 text-[11px] rounded-full font-bold {{ $status === 'belum_lengkap' ? 'bg-brand/10 text-brand' : 'bg-neutral-secondary text-body-subtle' }}">
            {{ $countBelumLengkap }}
        </span>
    </a>
    <a href="{{ route('admin.buku-registrasi.index', array_merge(request()->except('page'), ['status' => 'lengkap'])) }}"
       class="px-5 py-3 border-b-2 font-semibold text-[14px] transition-all flex items-center gap-2 {{ $status === 'lengkap' ? 'border-brand text-brand bg-white/40 rounded-t-lg shadow-sm' : 'border-transparent text-body-subtle hover:text-heading hover:border-border-default-strong' }}">
        <i class="fa-solid fa-circle-check"></i>
        <span>Sudah Lengkap / Selesai</span>
        <span class="px-2 py-0.5 text-[11px] rounded-full font-bold {{ $status === 'lengkap' ? 'bg-brand/10 text-brand' : 'bg-neutral-secondary text-body-subtle' }}">
            {{ $countSudahLengkap }}
        </span>
    </a>
</div>

<!-- Data Grid Table -->
<div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
            <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                <tr>
                    <th class="px-6 py-3 font-medium w-[3%]">No</th>
                    <th class="px-6 py-3 font-medium w-[8%]">Foto</th>
                    <th class="px-6 py-3 font-medium">Nama &amp; NIK</th>
                    <th class="px-6 py-3 font-medium">Alamat</th>
                    <th class="px-6 py-3 font-medium">Organisasi</th>
                    <th class="px-6 py-3 font-medium">SK Advokat</th>
                    <th class="px-6 py-3 font-medium">BAS &amp; Sumpah</th>
                    <th class="px-6 py-3 font-medium text-center w-[12%]">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white/60 divide-y divide-border-default">
                @forelse($registrasi as $index => $item)
                @php
                    $pemohon = $item->pemohon;
                    $permohonan = $item->permohonan;
                @endphp
                <tr class="hover:bg-neutral-secondary-soft transition-colors">
                    <!-- Nomor -->
                    <td class="px-6 py-4">{{ $registrasi->firstItem() + $index }}</td>
                    
                    <!-- Foto -->
                    <td class="px-6 py-4">
                        @if($pemohon && $pemohon->foto)
                            <a href="{{ asset('storage/' . $pemohon->foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pemohon->foto) }}" alt="Foto Pemohon" class="w-12 h-12 object-cover rounded shadow-sm border border-border-default hover:scale-105 transition-transform" style="width: 48px !important; height: 48px !important; min-width: 48px !important; min-height: 48px !important; max-width: 48px !important; max-height: 48px !important; object-fit: cover !important; aspect-ratio: 1/1 !important;">
                            </a>
                        @else
                            <div class="w-12 h-12 bg-neutral-secondary-soft rounded border border-border-default flex items-center justify-center text-body-subtle">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>
                        @endif
                    </td>

                    <!-- Nama & NIK -->
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="font-bold text-heading text-[15px]">{{ $pemohon->nama_lengkap ?? '-' }}</div>
                        <div class="text-[13px] text-body-subtle mt-1 font-mono font-semibold">NIK: {{ $pemohon->nik ?? '-' }}</div>
                    </td>

                    <!-- Alamat -->
                    <td class="px-6 py-4 whitespace-normal max-w-[200px]">
                        <span class="line-clamp-2" title="{{ $pemohon->alamat ?? '-' }}">{{ $pemohon->alamat ?? '-' }}</span>
                    </td>

                    <!-- Organisasi -->
                    <td class="px-6 py-4">
                        <span class="font-semibold text-heading">{{ $pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                    </td>

                    <!-- SK Advokat -->
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="font-medium text-heading">No: {{ $permohonan->nomor_sk ?? $pemohon->nomor_sk ?? '-' }}</div>
                        <div class="text-[12px] text-body-subtle mt-1">Tgl: {{ ($permohonan->tanggal_sk ?? $pemohon->tanggal_sk) ? \Carbon\Carbon::parse($permohonan->tanggal_sk ?? $pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}</div>
                    </td>

                    <!-- BAS & Sumpah -->
                    <td class="px-6 py-4 whitespace-normal">
                        @if($item->nomor_bas)
                            <div class="text-heading font-medium text-[14px]">BAS: <span class="font-mono font-semibold text-brand">{{ $item->nomor_bas }}</span></div>
                            <div class="text-[14px] text-heading font-medium mt-0.5"><i class="fa-solid fa-calendar mr-1 text-brand"></i>Sumpah: {{ \Carbon\Carbon::parse($item->tanggal_disumpah)->translatedFormat('d M Y') }}</div>
                            <div class="text-[14px] text-body-subtle mt-0.5">Oleh: {{ $item->ketua_pengadilan_tinggi }}</div>
                        @else
                            <span class="inline-flex items-center w-fit px-2 py-0.5 rounded text-xs font-semibold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Belum Lengkap
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5 font-bold">
                            <!-- Detail Member -->
                            <a href="{{ route('admin.buku-registrasi.show-member', $item->id) }}" title="Lihat Detail Anggota" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <!-- Edit Sumpah -->
                            @if($item->status_pemeriksa === 'Disetujui')
                                <span title="Data Terkunci (Sudah Disetujui Pemeriksa)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </span>
                            @elseif($item->permohonan && $item->permohonan->status === 'Selesai')
                                <a href="{{ route('admin.buku-registrasi.edit', $item->id) }}" title="Lengkapi Data Sumpah &amp; BAS" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                            @else
                                <button type="button" disabled title="Permohonan belum berstatus Selesai (Tidak dapat diisi)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </button>
                            @endif
                            <!-- Print Card -->
                            <a href="{{ route('admin.buku-registrasi.print', $item->id) }}" target="_blank" title="Cetak Data" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-success shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                <i class="fa-solid fa-print text-xs"></i>
                            </a>
                            <!-- Hapus Data -->
                            @if($item->status_pemeriksa === 'Disetujui')
                                <span title="Data Terkunci (Sudah Disetujui Pemeriksa)" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed opacity-60">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </span>
                            @else
                                <form action="{{ route('admin.buku-registrasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data buku registrasi {{ addslashes($pemohon->nama_lengkap ?? '') }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Data" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger hover:bg-danger hover:text-white shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-body-subtle italic">Data buku registrasi advokat tidak ditemukan.</td>
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
@endsection
