<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>REKAP DATA GURU DAN TENAGA PENDIDIK</title>
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        h3, h5 { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 5px 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; text-align: center; font-size: 10px; text-uppercase; }
        .header-kop { border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header-kop text-center">
        <h3>LAPORAN DAFTAR TENAGA PENDIDIK & KEPEGAWAIAN</h3>
        <h5>SISTEM INFORMASI MANAJEMEN AKADEMIK (SIMAK)</h5>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                <th>NAMA GURU</th>
                <th style="width: 70px;">KODE</th>
                <th style="width: 120px;">NIP / NIK</th>
                <th style="width: 90px;">JENIS PTK</th>
                <th style="width: 110px;">STATUS KEPEG.</th>
                <th style="width: 100px;">PENDIDIKAN</th>
                <th style="width: 80px;">WALI KELAS</th>
                <th style="width: 90px;">NO TELEPON</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gurus as $index => $g)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $g->nama_lengkap }}</td>
                <td class="text-center">({{ $g->kode_guru ?? $g->id }})</td>
                <td>NIP: {{ $g->nip ?? '-' }}<br><small>NIK: {{ $g->nik ?? '-' }}</small></td>
                <td>{{ $g->jenis_ptk ?? '-' }}</td>
                <td>{{ $g->status_kepegawaian ?? '-' }}</td>
                <td>{{ $g->pendidikan_terakhir ?? '-' }} {{ $g->jurusan_pendidikan ? '('.$g->jurusan_pendidikan.')' : '' }}</td>
                <td class="text-center">{{ $g->waliKelas->nama_kelas ?? '-' }}</td>
                <td>{{ $g->no_hp ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-3">Tidak ada data guru.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>