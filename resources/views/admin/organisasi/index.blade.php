@extends('layouts.admin')
@section('title', 'Master Organisasi Advokat')

@section('actions')
<a href="{{ route('admin.organisasi.create') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fa-solid fa-plus mr-2"></i> Tambah Organisasi
</a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(session('success'))
    <div class="p-4 rounded-xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3">
        <i class="fa-solid fa-circle-check mt-1"></i>
        <p class="text-[14px]">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
        <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft flex justify-between items-center">
            <div>
                <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                    <i class="fa-solid fa-building-columns text-brand"></i> Master Data Organisasi Advokat
                </h6>
                <p class="text-sm text-body-subtle mt-1">Kelola data organisasi advokat mitra dan usulan organisasi baru.</p>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap text-[14px] text-body" id="organisasiTable">
                    <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                        <tr>
                            <th class="px-6 py-4 font-medium w-[80px]">No</th>
                            <th class="px-6 py-4 font-medium">Nama Organisasi</th>
                            <th class="px-6 py-4 font-medium w-[150px]">Singkatan</th>
                            <th class="px-6 py-4 font-medium w-[180px]">Status</th>
                            <th class="px-6 py-4 font-medium w-[150px]">Total Anggota</th>
                            <th class="px-6 py-4 font-medium w-[150px]">Total Permohonan</th>
                            <th class="px-6 py-4 font-medium w-[150px]">Dibuat Pada</th>
                            <th class="px-6 py-4 font-medium w-[150px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/60 divide-y divide-border-default">
                        <!-- DataTables will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#organisasiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.organisasi.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_organisasi', name: 'nama_organisasi'},
                {data: 'singkatan', name: 'singkatan'},
                {data: 'status_badge', name: 'status'},
                {data: 'pemohons_count', name: 'pemohons_count', searchable: false},
                {data: 'permohonans_count', name: 'permohonans_count', searchable: false},
                {data: 'tanggal_dibuat', name: 'created_at', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush
