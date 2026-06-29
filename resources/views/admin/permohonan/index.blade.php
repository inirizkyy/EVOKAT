@extends('layouts.admin')
@section('title', 'Manajemen Permohonan')

@section('content')
<div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
    <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft flex justify-between items-center">
        <div>
            <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                <i class="fa-solid fa-list-check text-brand"></i> Daftar Permohonan Sumpah Advokat
            </h6>
            <p class="text-sm text-body-subtle mt-1">Kelola dan verifikasi seluruh permohonan yang masuk.</p>
        </div>
    </div>
    <div class="p-6">
        <!-- Wrapper matching tables.md specs -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body" id="permohonanTable">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Nomor Registrasi</th>
                        <th class="px-6 py-4 font-medium">Nama Pemohon</th>
                        <th class="px-6 py-4 font-medium">NIK</th>
                        <th class="px-6 py-4 font-medium">Organisasi</th>
                        <th class="px-6 py-4 font-medium">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white/60 divide-y divide-border-default">
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#permohonanTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.permohonan.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nomor_permohonan', name: 'nomor_permohonan'},
                {data: 'nama_pemohon', name: 'pemohon.nama_lengkap'},
                {data: 'nik', name: 'pemohon.nik'},
                {data: 'organisasi', name: 'pemohon.organisasi.nama_organisasi'},
                {data: 'tanggal_pengajuan', name: 'tanggal_pengajuan'},
                {data: 'status_badge', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
</script>
@endpush
