@extends('layouts.admin')
@section('title', 'Manajemen Permohonan')

@section('content')
<div class="bg-neutral-primary-soft rounded-base border border-border-default shadow-md mb-8">
    <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
        <h6 class="m-0 font-bold text-heading">Daftar Permohonan Sumpah Advokat</h6>
    </div>
    <div class="p-0">
        <!-- Wrapper matching tables.md specs -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap text-[14px] text-body" id="permohonanTable">
                <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                    <tr>
                        <th class="px-6 py-3 font-medium">No</th>
                        <th class="px-6 py-3 font-medium">Nomor Registrasi</th>
                        <th class="px-6 py-3 font-medium">Nama Pemohon</th>
                        <th class="px-6 py-3 font-medium">NIK</th>
                        <th class="px-6 py-3 font-medium">Organisasi</th>
                        <th class="px-6 py-3 font-medium">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-neutral-primary divide-y divide-border-default">
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
