<!DOCTYPE html>
<html>
<head>
    <title>Pesan Baru Live Chat (Admin Offline)</title>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Times New Roman', serif; line-height: 1.8; color: #222; background: #f5f5f5; margin:0; padding:0;">
    <div style="max-width: 680px; margin: 30px auto; background: #fff; border: 1px solid #ccc; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">

        {{-- Header --}}
        <div style="background: #951d18; padding: 24px 32px; text-align: center;">
            <h2 style="margin: 0; color: #fff; font-size: 18px; letter-spacing: 1px; text-transform: uppercase;">EVOKAT</h2>
            <p style="margin: 4px 0 0; color: #f5d6d5; font-size: 13px;">Sistem Pendaftaran Sumpah Advokat — Pemberitahuan Chat</p>
        </div>

        {{-- Judul Surat --}}
        <div style="padding: 20px 32px 0 32px; border-bottom: 2px solid #e0e0e0;">
            <h3 style="text-align: center; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; color: #951d18; margin-bottom: 4px;">
                PESAN BARU (ADMIN OFFLINE)
            </h3>
            <p style="text-align: center; font-size: 13px; color: #555; margin: 0 0 16px;">
                Tanggal: <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y - H:i') }} WIB</strong>
            </p>
        </div>

        {{-- Isi Surat --}}
        <div style="padding: 24px 32px;">
            <p style="margin: 0 0 16px;">Yth. Tim Admin EVOKAT,</p>
            <p style="margin: 0 0 16px; text-align: justify;">
                Sehubungan dengan tidak adanya Admin yang online saat ini, berikut adalah pesan masuk baru dari pengunjung website (Offline Live Chat):
            </p>

            {{-- Visitor Info --}}
            <div style="background: #fdfaf2; border-left: 5px solid #e2981a; border-radius: 4px; padding: 16px 20px; margin: 20px 0;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 5px 0; width: 30%; color: #555;">Nama Pengirim</td>
                        <td style="padding: 5px 0; width: 4%; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;">{{ $session->nama }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #555;">Email</td>
                        <td style="padding: 5px 0; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;"><a href="mailto:{{ $session->email }}" style="color: #951d18; text-decoration: none;">{{ $session->email }}</a></td>
                    </tr>
                    @if($session->no_hp)
                    <tr>
                        <td style="padding: 5px 0; color: #555;">No. HP</td>
                        <td style="padding: 5px 0; color: #555;">:</td>
                        <td style="padding: 5px 0; font-weight: bold;">{{ $session->no_hp }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- Chat Content --}}
            <div style="background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                <strong style="color: #951d18; font-size: 14px;">Pertanyaan/Pesan:</strong>
                <p style="margin: 8px 0 0; color: #333; text-align: justify; font-size: 14px; white-space: pre-line;">{!! nl2br(e($chat->message)) !!}</p>
            </div>

            <p style="margin: 24px 0 12px; text-align: justify;">
                Silakan masuk ke panel admin Live Chat EVOKAT untuk merespon pesan ini lebih lanjut jika diperlukan.
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ url('/admin/chat') }}" style="background-color: #951d18; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    Buka Panel Live Chat Admin
                </a>
            </div>

            <p style="margin: 24px 0 24px; text-align: justify; font-size: 12px; color: #666; font-style: italic; border-top: 1px dashed #ddd; padding-top: 15px;">
                *Catatan: Jika Anda tidak menemukan email pemberitahuan ini di folder Kotak Masuk (Inbox), silakan periksa folder Spam Anda.*
            </p>
        </div>

        {{-- Footer --}}
        <div style="background: #f0f4f8; padding: 12px 32px; border-top: 1px solid #e0e0e0; text-align: center;">
            <p style="margin: 0; font-size: 11px; color: #888;">
                Email ini dikirim secara otomatis oleh sistem EVOKAT.
            </p>
        </div>
    </div>
</body>
</html>
