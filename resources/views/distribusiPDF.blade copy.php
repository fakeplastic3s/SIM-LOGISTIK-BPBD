<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Serah Terima</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div style="text-align: center">
        <h3>PEMERINTAH KABUPATEN PEKALONGAN</h3>
        <h4>BADAN PENANGGULANGAN BENCANA DAERAH</h4>
        <p>Berita Acara Serah Terima Bantuan Logistik</p>
    </div>

    <p>
        Pada hari ini, <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('l') }}</b>
        tanggal <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->day }}</b>
        bulan <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('F') }}</b>
        tahun <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->year }}</b>, telah diserahkan bantuan logistik oleh PIHAK PERTAMA kepada PIHAK KEDUA.
    </p>

    <p>
        Nama Penerima: <b>{{ $distribusi->nama_penerima }}</b><br>
        Alamat Penerima: <b>{{ $distribusi->alamat_penerima }}</b><br>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($distribusi->detailBarangKeluar as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $detail->stokBarang->barang->nama_barang ?? '-' }}</td>
                <td>{{ $detail->stokBarang->merk ?? '-' }}</td>
                <td>{{ $detail->jumlah_keluar }}</td>
                <td>{{ $detail->satuan }}</td>
                <td>Baik</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Dengan ini PIHAK KEDUA menyatakan telah menerima bantuan logistik tersebut dengan baik.</p>

    <div style="margin-top: 30px; text-align: center">
        <div style="float: left; width: 50%">
            <p>PIHAK PERTAMA</p>
            <p>Kepala Pelaksana BPBD</p>
            <br /><br /><br />
            <p><b>Budi Rahardjo, AP, M.AP</b></p>
            <p>NIP. 197601131994121001</p>
        </div>
        <div style="float: right; width: 50%">
            <p>PIHAK KEDUA</p>
            <p>{{ $distribusi->nama_penerima }}</p>
        </div>
    </div>
</body>
</html>
