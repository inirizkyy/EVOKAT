@extends('layouts.admin')
@section('title', 'Manajemen Berita')

@section('actions')
<a href="{{ route('admin.berita.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-[14px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Berita
</a>
@endsection

@section('content')
<div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
    <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft flex justify-between items-center">
        <div>
            <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                <i class="fa-solid fa-newspaper text-brand"></i> Daftar Publikasi Berita
            </h6>
            <p class="text-sm text-body-subtle mt-1">Kelola konten berita dan informasi terkait sumpah advokat.</p>
        </div>
    </div>
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-4 font-medium w-[5%]">No</th>
                        <th class="px-6 py-4 font-medium w-[15%]">Thumbnail</th>
                        <th class="px-6 py-4 font-medium">Judul Berita</th>
                        <th class="px-6 py-4 font-medium w-[15%]">Tgl Publish</th>
                        <th class="px-6 py-4 font-medium w-[20%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    @forelse($berita as $index => $item)
                    <tr class="hover:bg-neutral-primary-soft transition-colors group">
                        <td class="px-6 py-4">{{ $berita->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            @if($item->thumbnail)
                                <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="Thumbnail" class="h-[60px] w-auto object-cover rounded-lg shadow-sm border border-border-default">
                            @else
                                <div class="h-[60px] w-[80px] bg-neutral-primary rounded-lg border border-border-default-medium border-dashed flex items-center justify-center text-body-subtle">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-heading whitespace-normal group-hover:text-brand transition-colors">{{ $item->judul }}</td>
                        <td class="px-6 py-4">
                            @if($item->published_at)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">{{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('d M Y') }}</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-neutral-secondary-medium border border-border-default text-heading">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-1">
                            <a href="{{ route('admin.berita.edit', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-white text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                <i class="fa-solid fa-edit text-xs mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-white text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                    <i class="fa-solid fa-trash text-xs mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-body-subtle">
                            <i class="fa-regular fa-folder-open text-4xl mb-3 block opacity-50"></i>
                            Belum ada berita yang diterbitkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-border-default bg-neutral-primary-soft/50">
            {{ $berita->links() }}
        </div>
    </div>
</div>
@endsection
