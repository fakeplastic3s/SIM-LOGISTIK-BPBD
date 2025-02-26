<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Distribusi</title>
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
        <h4 style="text-align: center; margin">SURAT PENGAJUAN DISTRIBUSI BANTUAN LOGISTIK</h4>

        <p>Pada hari ini <b>{{ \Carbon\Carbon::parse($pengajuanDistribusi->tanggal_pengajuan)->translatedFormat('l') }}</b>, <b>{{ \Carbon\Carbon::parse($pengajuanDistribusi->tanggal_pengajuan)->day }} {{ \Carbon\Carbon::parse($pengajuanDistribusi->tanggal_pengajuan)->translatedFormat('F') }} {{ \Carbon\Carbon::parse($pengajuanDistribusi->tanggal_pengajuan)->year }}</b>, yang bertanda tangan dibawah ini.
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

                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuanDistribusi->detailPengajuan as $key => $detail)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td style="text-align: left; margin-left:10px;">{{ $detail->nama_barang }}</td>
                    <td>{{ $detail->jumlah_pengajuan }} {{ $detail->satuan }}</td>
                </tr>
                @endforeach
                
            </tbody>
        </table>
        <p>Bantuan ini ditujukan untuk korban bencana A/n  {{ $pengajuanDistribusi->nama_penerima }}. Alamat {{ $pengajuanDistribusi->alamat_penerima }}. <br>Selanjutnya PIHAK KEDUA bertanggungjawab atas menyalurkan bantuan logistik yang dimaksud</p>
    </div>


</body>

</html>
