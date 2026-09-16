<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi - {{ $pembayaran->kode_transaksi }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 20px; color: #333; }
        .box { border: 2px solid #333; padding: 20px; max-width: 600px; margin: auto; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table td { padding: 6px 0; vertical-align: top; }
        .total { font-weight: bold; font-size: 15px; text-align: right; margin-top: 15px; background: #f8f9fa; padding: 10px; border-radius: 4px; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; text-align: center; }
    </style>
</head>
<body onload="window.print()">

<div class="box">
    <div class="header">
        <h2 style="margin: 0; text-transform: uppercase;">SIM SEKOLAH MODERN</h2>
        <p style="margin: 5px 0 0 0; font-weight: bold;">BUKTI PEMBAYARAN RESMI</p>
    </div>

    <table class="table">
        <tr>
            <td width="35%"><strong>No. Transaksi</strong></td>
            <td>: {{ $pembayaran->kode_transaksi }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pembayaran</strong></td>
            <td>: {{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Nama Siswa</strong></td>
            <td>: {{ $pembayaran->siswa->nama_lengkap }} (NISN: {{ $pembayaran->siswa->nisn }})</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>: {{ $pembayaran->siswa->kelas }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Pembayaran</strong></td>
            <td>: {{ $pembayaran->jenisPembayaran->nama_pembayaran }} {{ $pembayaran->bulan_dibayar ? '('.$pembayaran->bulan_dibayar.' '.$pembayaran->tahun_dibayar.')' : '' }}</td>
        </tr>
    </table>

    <div class="total">
        TOTAL DIBAYAR: Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
        <br><span style="color: green; font-size: 11px;">STATUS: LUNAS</span>
    </div>

    <div class="footer">
        <div>
            <p>Siswa / Penyetor,</p>
            <br><br><br>
            <p>___________________</p>
        </div>
        <div>
            <p>Petugas Kasir / Admin,</p>
            <br><br><br>
            <p><strong>{{ $pembayaran->admin->name }}</strong></p>
        </div>
    </div>
</div>

</body>
</html>