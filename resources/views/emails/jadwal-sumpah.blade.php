<!DOCTYPE html>
<html>
<head>
    <title>Surat Pemberitahuan Jadwal Pelaksanaan Sumpah Advokat</title>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Times New Roman', serif; line-height: 1.8; color: #222; background: #f5f5f5; margin:0; padding:0;">
    <div style="max-width: 680px; margin: 30px auto; background: #fff; border: 1px solid #ccc; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">

        {{-- Header --}}
        <div style="background: #1a3c5e; padding: 24px 32px; text-align: center;">
            <h2 style="margin: 0; color: #fff; font-size: 18px; letter-spacing: 1px; text-transform: uppercase;">EVOKAT</h2>
            <p style="margin: 4px 0 0; color: #b3d4f0; font-size: 13px;">Sistem Pendaftaran Sumpah Advokat</p>
        </div>

        {{-- Judul Surat --}}
        <div style="padding: 20px 32px 0 32px; border-bottom: 2px solid #e0e0e0;">
            <h3 style="text-align: center; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; color: #1a3c5e; margin-bottom: 4px;">
                SURAT PEMBERITAHUAN
            </h3>
            <p style="text-align: center; font-size: 13px; color: #555; margin: 0 0 16px;">
                Nomor: <strong>{{ $jadwal->permohonan->nomor_permohonan }}</strong> &nbsp;|&nbsp;
                Tanggal: <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</strong>
            </p>
        </div>

        {{-- Isi Surat --}}
        <div style="padding: 24px 32px;">
            <p style="margin: 0 0 16px;">Yth.</p>
            <p style="margin: 0 0 4px; font-weight: bold;">{{ $jadwal->permohonan->pemohon->nama_lengkap }}</p>
            <p style="margin: 0 0 16px; color: #555; font-size: 13px;">{{ $jadwal->permohonan->pemohon->alamat ?? '' }}</p>

            <p style="margin: 0 0 16px; text-align: justify;">
                Dengan hormat,
            </p>
            <p style="margin: 0 0 16px; text-align: justify;">
                Sehubungan dengan permohonan pengambilan Sumpah Advokat yang telah Saudara/i ajukan dengan nomor registrasi
                <strong>{{ $jadwal->permohonan->nomor_permohonan }}</strong>, dengan ini kami sampaikan bahwa berkas permohonan Saudara/i
                telah <strong style="color: #1a7c3e;">dinyatakan lengkap dan memenuhi syarat</strong>.
            </p>
            <p style="margin: 0 0 20px; text-align: justify;">
                Saudara/i dijadwalkan untuk mengikuti Pengambilan Sumpah Advokat pada:
            </p>

            {{-- Info Jadwal --}}
            <div style="background: #f0f7f0; border-left: 5px solid #27ae60; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 5px 0; width: 38%; color: #555;">Hari / Tanggal</td>
                        <td style="padding: 5px 0; width: 4%; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('l') }},
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #555;">Pukul</td>
                        <td style="padding: 5px 0; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;">
                            {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} WIB
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #555;">Tempat / Lokasi</td>
                        <td style="padding: 5px 0; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;">{{ $jadwal->lokasi }}</td>
                    </tr>
                    @if($jadwal->keterangan)
                    <tr>
                        <td style="padding: 5px 0; color: #555; vertical-align: top;">Catatan</td>
                        <td style="padding: 5px 0; color: #555; vertical-align: top;">:</td>
                        <td style="padding: 5px 0;">{{ $jadwal->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- Data Pemohon --}}
            <p style="margin: 0 0 10px; font-weight: bold; font-size: 13px; color: #1a3c5e;">Data Pemohon:</p>
            <div style="background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; padding: 12px 20px; margin: 0 0 20px; font-size: 13px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; width: 38%; color: #666;">Nama Lengkap</td>
                        <td style="width:4%">:</td>
                        <td style="padding: 4px 0;">{{ $jadwal->permohonan->pemohon->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #666;">NIK</td>
                        <td>:</td>
                        <td style="padding: 4px 0;">{{ $jadwal->permohonan->pemohon->nik }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #666;">No. Permohonan</td>
                        <td>:</td>
                        <td style="padding: 4px 0;">{{ $jadwal->permohonan->nomor_permohonan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #666;">Organisasi Advokat</td>
                        <td>:</td>
                        <td style="padding: 4px 0;">{{ $jadwal->permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin: 0 0 12px; text-align: justify;">
                Mohon hadir tepat waktu dengan membawa <strong>berkas-berkas asli</strong> yang disyaratkan serta mematuhi protokol
                dan tata tertib persidangan (antara lain: mengenakan pakaian Toga beserta atribut sesuai ketentuan).
            </p>
            <p style="margin: 0 0 24px; text-align: justify;">
                Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian dan kehadiran Saudara/i, kami ucapkan terima kasih.
            </p>

            <p style="margin: 0 0 4px;">Hormat kami,</p>
            <br>
            <p style="margin: 0; font-weight: bold;">Panitia Pengambilan Sumpah Advokat</p>
            <p style="margin: 0; font-size: 13px; color: #555;">EVOKAT — Sistem Pendaftaran Sumpah Advokat</p>
        </div>

        {{-- Footer --}}
        <div style="background: #f0f4f8; padding: 12px 32px; border-top: 1px solid #e0e0e0; text-align: center;">
            <p style="margin: 0; font-size: 11px; color: #888;">
                Email ini dikirim secara otomatis oleh sistem EVOKAT. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
