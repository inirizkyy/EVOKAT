@extends('layouts.admin')
@section('title', 'Master Persyaratan')

@section('actions')
<a href="{{ route('admin.persyaratan.create') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Syarat
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
                        <th class="px-6 py-3 font-medium w-[25%]">Nama Persyaratan</th>
                        <th class="px-6 py-3 font-medium w-[40%]">Deskripsi</th>
                        <th class="px-6 py-3 font-medium text-center w-[10%]">Sifat</th>
                        <th class="px-6 py-3 font-medium text-center w-[15%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    @forelse($persyaratan as $index => $item)
                    <tr class="hover:bg-neutral-secondary-soft transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-heading whitespace-normal">{{ $item->nama_persyaratan }}</td>
                        <td class="px-6 py-4 whitespace-normal text-body-subtle">{{ $item->deskripsi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_required)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Wajib</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Opsional</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.persyaratan.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                <i class="fa-solid fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.persyaratan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus syarat ini?')">
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
                        <td colspan="5" class="px-6 py-4 text-center text-body-subtle">Belum ada master data persyaratan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
