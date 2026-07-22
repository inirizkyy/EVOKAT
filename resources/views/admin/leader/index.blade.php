@extends('layouts.admin')
@section('title', 'Kelola Pemimpin Sumpah')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah Pemimpin Sumpah -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default p-6 h-fit">
        <h5 class="font-bold text-heading mb-4 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-brand"></i> Tambah Pemimpin Sumpah
        </h5>
        
        @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.leader.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">Nama Pejabat / Jabatan <span class="text-fg-danger">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Ketua, Wakil, atau Plh. Ketua" 
                       class="block w-full rounded-base border border-border-default-medium bg-white text-sm py-2.5 px-3 focus:outline-none focus:border-brand transition-all">
            </div>
            
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-full text-sm font-bold bg-brand text-white shadow-sm hover:shadow-md hover:bg-brand-strong transition-all">
                <i class="fa-solid fa-save mr-2"></i> Simpan Pemimpin Sumpah
            </button>
        </form>
    </div>

    <!-- Daftar Pemimpin Sumpah -->
    <div class="lg:col-span-2 space-y-6">


        <div class="bg-white rounded-xl shadow-sm border border-border-default overflow-hidden">
            <div class="p-6 border-b border-border-default bg-neutral-secondary-soft">
                <h5 class="font-bold text-heading m-0 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-brand"></i> Daftar Pemimpin Sumpah Terdaftar
                </h5>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap text-sm text-body">
                    <thead class="bg-neutral-secondary-soft/50 border-b border-border-default text-body font-medium">
                        <tr>
                            <th class="px-6 py-3 font-medium w-[10%]">No</th>
                            <th class="px-6 py-3 font-medium">Nama Pejabat / Jabatan</th>
                            <th class="px-6 py-3 font-medium text-center w-[20%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-default bg-white">
                        @forelse($leaders as $index => $leader)
                            <tr class="hover:bg-neutral-secondary-soft/35 transition-colors">
                                <td class="px-6 py-4">{{ $leaders->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-semibold text-heading">{{ $leader->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.leader.destroy', $leader->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemimpin sumpah ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-danger hover:bg-danger hover:text-white border border-border-default transition-all shadow-sm" title="Hapus Pemimpin Sumpah">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-body-subtle italic">Belum ada data pemimpin sumpah terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaders->hasPages())
                <div class="p-4 border-t border-border-default">
                    {{ $leaders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
