<table>
    <thead>
        <tr>
            <th colspan="11" style="font-weight: bold; font-size: 14px; text-align: center;">PENGADILAN TINGGI TANJUNGKARANG</th>
        </tr>
        <tr>
            <th colspan="11" style="font-weight: bold; font-size: 12px; text-align: center;">BUKU REGISTRASI ADVOKAT</th>
        </tr>
        <tr>
            <th colspan="11" style="text-align: center;">Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">No</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Nama Lengkap</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">NIK</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Alamat</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Organisasi Advokat</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Nomor SK Advokat</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Tanggal SK Advokat</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Nomor BAS</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Tanggal Sumpah</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Ketua PT Penyumpah</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000;">Nama Saksi-Saksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registrasi as $index => $item)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $item->pemohon->nama_lengkap ?? '-' }}</td>
            <td style="border: 1px solid #000; mso-number-format:'\@';">{{ $item->pemohon->nik ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->pemohon->alamat ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->pemohon->organisasi->nama_organisasi ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->permohonan->nomor_sk ?? $item->pemohon->nomor_sk ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ ($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk)->format('Y-m-d') : '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->nomor_bas ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->tanggal_disumpah ? \Carbon\Carbon::parse($item->tanggal_disumpah)->format('Y-m-d') : '-' }}</td>
            <td style="border: 1px solid #000;">{{ $item->ketua_pengadilan_tinggi ?? '-' }}</td>
            <td style="border: 1px solid #000;">
                @if($item->saksi)
                    @php $saksiArray = explode(';', $item->saksi); @endphp
                    1. {{ trim($saksiArray[0] ?? '-') }}, 2. {{ trim($saksiArray[1] ?? '-') }}
                @else
                    -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
