@extends('layouts.admin')
@section('title', 'Jadwal Sumpah')

@section('actions')
<a href="{{ route('admin.jadwal.create') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Jadwal
</a>
@endsection

@section('content')
<div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[5%]">No</th>
                        <th class="px-6 py-3 font-medium">Nomor Registrasi</th>
                        <th class="px-6 py-3 font-medium">Nama Pemohon</th>
                        <th class="px-6 py-3 font-medium">Waktu Pelaksanaan</th>
                        <th class="px-6 py-3 font-medium">Lokasi</th>
                        <th class="px-6 py-3 font-medium text-center w-[15%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    @forelse($jadwals as $index => $item)
                    <tr class="hover:bg-neutral-secondary-soft transition-colors">
                        <td class="px-6 py-4">{{ $jadwals->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">{{ $item->permohonan->nomor_permohonan }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-heading">{{ $item->permohonan->pemohon->nama_lengkap }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center"><i class="fa-regular fa-calendar mr-2 text-brand"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs mt-1 text-body-subtle flex items-center"><i class="fa-regular fa-clock mr-2 text-danger"></i> {{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.jadwal.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                <i class="fa-solid fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default ml-2">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-body-subtle">Belum ada jadwal sumpah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border-default">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection
