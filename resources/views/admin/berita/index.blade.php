@extends('layouts.admin')
@section('title', 'Manajemen Berita')

@section('actions')
<a href="{{ route('admin.berita.create') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Berita
</a>
@endsection

@section('content')
<div class="bg-neutral-primary-soft rounded-base border border-border-default shadow-md mb-8">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium w-[5%]">No</th>
                        <th class="px-6 py-3 font-medium w-[15%]">Thumbnail</th>
                        <th class="px-6 py-3 font-medium">Judul Berita</th>
                        <th class="px-6 py-3 font-medium w-[15%]">Tgl Publish</th>
                        <th class="px-6 py-3 font-medium w-[20%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-neutral-primary divide-y divide-border-default">
                    @forelse($berita as $index => $item)
                    <tr class="hover:bg-neutral-secondary-soft transition-colors">
                        <td class="px-6 py-4">{{ $berita->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            @if($item->thumbnail)
                                <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="Thumbnail" class="h-[60px] w-auto object-cover rounded-base shadow-sm border border-border-default">
                            @else
                                <span class="text-xs text-body-subtle">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-heading whitespace-normal">{{ $item->judul }}</td>
                        <td class="px-6 py-4">
                            @if($item->published_at)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">{{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default mr-1">
                                <i class="fa-solid fa-edit text-xs mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                    <i class="fa-solid fa-trash text-xs mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-body-subtle">Belum ada berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border-default">
            {{ $berita->links() }}
        </div>
    </div>
</div>
@endsection
