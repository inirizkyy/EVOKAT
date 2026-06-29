@extends('layouts.admin')
@section('title', 'Manajemen FAQ')

@section('actions')
<a href="{{ route('admin.faq.create') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-plus mr-2"></i> Tambah FAQ
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
                        <th class="px-6 py-3 font-medium w-[35%]">Pertanyaan</th>
                        <th class="px-6 py-3 font-medium w-[45%]">Jawaban</th>
                        <th class="px-6 py-3 font-medium w-[15%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    @forelse($faqs as $index => $item)
                    <tr class="hover:bg-neutral-secondary-soft transition-colors">
                        <td class="px-6 py-4">{{ $faqs->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-bold text-heading whitespace-normal">{{ $item->pertanyaan }}</td>
                        <td class="px-6 py-4 whitespace-normal text-body-subtle">{{ Str::limit($item->jawaban, 100) }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.faq.edit', $item->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-base bg-neutral-primary-soft text-warning shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default mr-1">
                                <i class="fa-solid fa-edit text-xs mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.faq.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus FAQ ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-body-subtle">Belum ada FAQ.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border-default">
            {{ $faqs->links() }}
        </div>
    </div>
</div>
@endsection
