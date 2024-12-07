<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Hasil Kegiatan Prakerin</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            margin-bottom: 20px;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2px ridge #000;
            padding-bottom: 10px;
            margin-bottom: -15px;
            justify-content: center;
        }

        .kop-surat img {
            width: 80px;
            margin-right: 10px;
            align-self: flex-start;
            position: absolute;
            left: 55;
            top: 20;
            bottom: 0;
        }

        .judul-kop h1 {
            text-align: center;
            font-size: 21px;
            margin-left: 0px;
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
            align-self: flex-end;
        }

        .judul-kop p {
            text-align: center;
            margin-left: 0px;
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
        }

        .judul-surat {
            text-align: center;
            margin: 0;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .judul-surat~p {
            margin: 0;
            text-align: center;
            font-weight: bold;
        }

        .content p {
            margin: 5px 0;
        }

        .content h2 {
            margin-top: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        table th {
            text-align: center;
        }

        footer {
            text-align: right;
            margin-top: 40px;
        }

        .tanda-tangan img {
            width: 150px;
            margin-top: -40px;
            margin-bottom: -30px;
            padding: 0px;
        }

        .tanda-tangan p {
            margin: 0;
            font-weight: bold;
        }

    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="kop-surat">
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('img/logo_bpbd.png')); ?>"
                    alt="UPT Komputer STMIK Widya Pratama Pekalongan">
                <div class="judul-kop">
                    <h1>Badan Penanggulangan Bencana Daerah <br> (BPBD)</h1>
                    <p>Jalan Sumbing Nomor 2 Kajen Kabupaten Pekalongan</p>
                    <p>Telpon (0285) 381905 fax.(0285) 3830912</p>
                </div>
            </div>
            <br>
            <h2 class="judul-surat">LAPORAN STOK LOGISTIK</h2>
            <p>Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </header>
        <main>
            <div class="content">
                <div >
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Barang</th>
                                {{-- <th>Merk</th> --}}
                                <th>Kedaluwarsa</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($data['barang'] as $item)
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>{{ $item->barang->nama_barang ?? '-' }} &nbsp;{{ $item->merk }}</td>
                                <td>{{ $item->tanggal_exp ? \Carbon\Carbon::parse($item->tanggal_exp)->translatedFormat('d F Y') : '-' }}</td>
                                <td>{{ $item->stok }} &nbsp;{{ $item->satuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </main>
        <footer>
            <p>Pekalongan,
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </p>
            <p style="margin-top: -17px;">Kepala Bidang Kedaruratan dan Logistik,</p>
            <div class="tanda-tangan">
                <br>
                <br>
                <p><u>Fulan bin Fulan</u></p>
                <p style="font-size: small">NPPY: 123123123</p>
            </div>
        </footer>
    </div>
    </div>
</body>