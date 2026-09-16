<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>JADWAL PELAJARAN SEKOLAH</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 9px; }
        h3, h5 { text-align: center; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; text-align: center; padding: 3px 2px; }
        th { background-color: #333; color: #fff; }
        .bg-head { background-color: #696cff; color: white; font-weight: bold; }
        .bg-istirahat { background-color: #ddd; font-weight: bold; }
        .slot-isi { background-color: #e8f5e9; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

    <h3>JADWAL PELAJARAN HARI & MINGGUAN</h3>
    <h5>TAHUN AJARAN {{ $taAktif->nama_tahun_ajaran ?? '-' }} - SEMESTER {{ strtoupper($taAktif->semester ?? '') }}</h5>

    <table>
        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
            @if(isset($jamPelajarans[$hari]))
            <thead>
                <tr>
                    <th style="width: 40px;" class="bg-head">{{ strtoupper($hari) }}</th>
                    <th style="width: 80px;" class="bg-head">WAKTU</th>
                    @foreach($kelases as $k)
                        <th class="bg-head">{{ $k->nama_kelas }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($jamPelajarans[$hari] as $jam)
                <tr>
                    <td>Jam {{ $jam->jam_ke }}</td>
                    <td>{{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }}</td>
                    @foreach($kelases as $k)
                        @php $jadwal = $jadwals[$jam->id][$k->id] ?? null; @endphp
                        @if($jam->is_istirahat)
                            <td class="bg-istirahat">ISTIRAHAT</td>
                        @elseif($jadwal)
                            <td class="slot-isi">
                                {{ strtoupper($jadwal->pengampu->mapel->kode_mapel ?? substr($jadwal->pengampu->mapel->nama_mapel, 0, 4)) }}({{ $jadwal->pengampu->guru->kode_guru ?? $jadwal->pengampu->guru->id }})
                            </td>
                        @else
                            <td>-</td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
            @endif
        @endforeach
    </table>

</body>
</html>