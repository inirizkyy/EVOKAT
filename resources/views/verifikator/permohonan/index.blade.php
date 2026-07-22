@extends('layouts.admin')
@section('title', 'Daftar Antrean Permohonan')

@section('content')
<div class="space-y-6">
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default overflow-hidden">
        <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft">
            <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                <i class="fa-solid fa-hourglass-half text-brand"></i> Antrean Permohonan Sumpah Advokat
            </h6>
            <p class="text-sm text-body-subtle mt-1">Daftar permohonan yang saat ini memerlukan verifikasi Anda.</p>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                    <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                        <tr>
                            <th class="px-6 py-4 font-medium w-[5%]">No</th>
                            <th class="px-6 py-4 font-medium">Nomor Registrasi</th>
                            <th class="px-6 py-4 font-medium">Organisasi</th>
                            <th class="px-6 py-4 font-medium">Nomor SK Pendirian</th>
                            <th class="px-6 py-4 font-medium text-center">Jumlah Anggota</th>
                            <th class="px-6 py-4 font-medium">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-center w-[10%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default bg-white">
                        @forelse($permohonans as $index => $row)
                            <tr class="hover:bg-neutral-secondary-soft/30 transition-colors">
                                <td class="px-6 py-4">{{ $permohonans->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-bold text-heading font-mono">{{ $row->nomor_permohonan }}</td>
                                <td class="px-6 py-4 font-semibold text-heading">{{ $row->organisasi->nama_organisasi ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $row->nomor_sk ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold">{{ $row->pemohons->count() }} Orang</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $role = Auth::user()->role;
                                        $statusVerifikator = null;
                                        if ($role === 'verifikator1') {
                                            $statusVerifikator = $row->status_verifikator1;
                                        } elseif ($role === 'verifikator2') {
                                            $statusVerifikator = $row->status_verifikator2;
                                        } elseif ($role === 'verifikator3') {
                                            $statusVerifikator = $row->status_verifikator3;
                                        } elseif ($role === 'verifikator4') {
                                            $statusVerifikator = $row->status_verifikator4;
                                        }
                                    @endphp

                                    @if(str_starts_with($row->status, 'Menunggu Perbaikan Dokumen'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                                            <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>Menunggu Perbaikan Pemohon
                                        </span>
                                    @elseif($statusVerifikator === 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">
                                            <i class="fa-solid fa-circle-check mr-1.5"></i>Sudah Disetujui
                                        </span>
                                    @elseif($statusVerifikator === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">
                                            <i class="fa-solid fa-circle-xmark mr-1.5"></i>Sudah Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning">
                                            <i class="fa-regular fa-clock mr-1.5"></i>Menunggu Verifikasi Anda
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route(Auth::user()->role . '.permohonan.show', $row->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-base text-xs font-bold bg-neutral-primary-soft text-brand border border-brand shadow-sm hover:shadow-md transition-all">
                                        <i class="fa-solid fa-file-shield mr-1"></i> Periksa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-body-subtle italic">Tidak ada antrean permohonan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($permohonans->hasPages())
                <div class="mt-4 border-t border-border-default pt-4">
                    {{ $permohonans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
