<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Serah Terima</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.6;
            /* margin: 20px; */
        }

        .kop-surat {
            text-align: center;
            border-bottom: 2px solid black;
            /* margin-bottom: 10px; */
            padding-bottom: 5px;
            position: relative;
        }

        .kop-surat img {
            position: absolute;
            left: 20;
            top: 0;
            width: 65px;
        }

        .kop-surat h4, .kop-surat h3, .kop-surat p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding-left: 8px;
            padding-right: 8px;
        }

        th {
            background-color: #f0f0f0;
        }

    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo_bpbd.png'))) }}" alt="Logo BPBD">
        <h4>PEMERINTAH KABUPATEN PEKALONGAN</h4>
        <h3>BADAN PENANGGULANGAN BENCANA DAERAH (BPBD)</h3>
        <p>Jalan Sumbing No. 2 Telpon (0285) 381905 Fax. (0285) 3830912 Kajen</p>
    </div>

    <div class="content">
        <h4 style="text-align: center; margin">BERITA ACARA SERAH TERIMA BANTUAN LOGISTIK</h4>

        <p>Pada hari ini <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('l') }}</b>, <b>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->day }} {{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->translatedFormat('F') }} {{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->year }}</b>, yang bertanda tangan dibawah ini.
        </p>

        <p>Nama : <b>Budi Rahardjo, AP, M.AP</b><br>
           Jabatan : <b>Kepala Pelaksana Badan Penanggulangan Bencana Daerah</b><br>
           Selanjutnya disebut <b>PIHAK PERTAMA</b>.
        </p>

        <p>Nama :<br>
           Alamat : <br>
           Selanjutnya disebut <b>PIHAK KEDUA</b>.</p>

        <p>Dengan ini <b>PIHAK PERTAMA</b> menyerahkan kepada <b>PIHAK KEDUA</b>. Dan <b>PIHAK KEDUA</b> menerima dari <b>PIHAK PERTAMA</b> bantuan logistik berupa:</p>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Logistik</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($distribusi->detailBarangKeluar as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td style="text-align: left; margin-left:10px;">{{ $detail->stokBarang->barang->nama_barang ?? '-' }} ({{ $detail->stokBarang->merk ?? '-' }})</td>
                    <td>{{ $detail->jumlah_keluar }} {{ $detail->satuan }}</td>
                    <td>Baik</td>
                </tr>
                @endforeach
                
            </tbody>
        </table>
        <p>Bantuan ini ditujukan untuk korban bencana A/n  {{ $distribusi->nama_penerima }}. Alamat {{ $distribusi->alamat_penerima }}. <br>Selanjutnya PIHAK KEDUA bertanggungjawab atas menyalurkan bantuan logistik yang dimaksud</p>
    </div>

    <div style="text-align: center; margin:0;">
        <div style="float: left; width: 50%">
            <p>Yang menerima,</p>
            <p><b>PIHAK KEDUA</b></p>
            <br>
            <br>
            <br>
            <br>
            <br>
            <p>....................................................</p>
        </div>
        <div style="float: right; width: 50%">
            <p> Yang menyerahkan, <br> <b>PIHAK PERTAMA</b> <br> Kepala Pelaksana BPBD <br> Kabupaten Pekalongan</p>
            <br /><br /><br />
            <p><b>Budi Rahardjo, AP, M.AP</b><br>
                NIP. 197601131994121001</p>
        </div>

    </div>
</body>

</html>
