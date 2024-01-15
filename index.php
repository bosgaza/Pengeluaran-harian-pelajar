<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencatatan Pengeluaran Keuangan Anak Sekolah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<?php
// Fungsi untuk menyimpan data pengeluaran ke file
function simpanPengeluaran($tanggal, $jumlah, $keterangan)
{
    $data = "$tanggal|$jumlah|$keterangan\n";
    file_put_contents('pengeluaran.txt', $data, FILE_APPEND);
}

// Fungsi untuk mendapatkan daftar pengeluaran
function ambilPengeluaran()
{
    $pengeluaran = [];

    if (file_exists('pengeluaran.txt')) {
        $lines = file('pengeluaran.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            list($tanggal, $jumlah, $keterangan) = explode('|', $line);
            $pengeluaran[] = [
                'tanggal' => $tanggal,
                'jumlah' => $jumlah,
                'keterangan' => $keterangan
            ];
        }
    }

    return $pengeluaran;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = date('Y-m-d'); // Tanggal otomatis
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Validasi input
    if (!empty($jumlah) && is_numeric($jumlah) && !empty($keterangan)) {
        // Simpan data ke file
        simpanPengeluaran($tanggal, $jumlah, $keterangan);
    } else {
        echo '<p style="color: red;">Mohon isi jumlah dan keterangan dengan benar.</p>';
    }
}
?>

<form method="post" action="">
    <h2>Pengeluaran Harian Anak Sekolah</h2>
    <label for="jumlah">Jumlah (IDR):</label>
    <input type="number" id="jumlah" name="jumlah" required>

    <label for="keterangan">Keterangan:</label>
    <input type="text" id="keterangan" name="keterangan" required>

    <input type="submit" value="Catat Pengeluaran">
</form>

<h2>Daftar Pengeluaran</h2>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Jumlah (IDR)</th>
        <th>Keterangan</th>
    </tr>
    <?php
    // Ambil dan tampilkan daftar pengeluaran
    $daftarPengeluaran = ambilPengeluaran();
    $totalPengeluaran = 0;

    foreach ($daftarPengeluaran as $pengeluaran) {
        echo '<tr>';
        echo '<td>' . $pengeluaran['tanggal'] . '</td>';
        echo '<td>' . $pengeluaran['jumlah'] . '</td>';
        echo '<td>' . $pengeluaran['keterangan'] . '</td>';
        echo '</tr>';

        // Hitung total pengeluaran
        $totalPengeluaran += $pengeluaran['jumlah'];
    }
    ?>
</table>

<h3>Total Pengeluaran: <?php echo $totalPengeluaran; ?> IDR</h3>

</body>
</html>
