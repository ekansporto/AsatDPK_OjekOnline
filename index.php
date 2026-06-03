<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="stylee.css">
</head>

<body>

    <h2>Sistem Ojek Online
    </h2>

    <form action="" method="POST" onsubmit="return validasi()">
        <label>Nama Pelanggan:</label><br>
        <input type="text" id="nama" name="nama" value=""><br>
        <label>No hp:</label><br>
        <input type="number" name="nohp" id="nohp" value=""><br>
        <div id="errorNoHp" class="error"></div>
        <label>Jarak Tempuh:</label><br>
        <input type="number" id="jarak" name="jarak" value=""><br>
        <label>Jenis Layanan:</label>
        <br>
        <select name="jenisLayanan">
            <option value="GoRide Reguler">GoRide Reguler</option>
            <option value="GoRide Prioritas">GoRide Prioritas</option>
            <option value="GoCar">GoCar</option>
            <option value="GoCar XL">GoCar XL</option>
            <option value="GoFood">GoFood</option>
        </select><br>
        <label>Kode Voucher:</label><br>
        <input type="text" id="kodeVoucher" name="kodeVoucher" value=""><br>
        <label>Metode Pembayaran:</label>
        <br>
        <select name="metodePembayaran">
            <option value="Transfer">Transfer</option>
            <option value="Ewallet">E-wallet</option>
            <option value="Cash">Cash</option>
        </select><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <script src="pipi.js"></script>
</body>

</html>
<?php
class User
{
    public static $totalTransaksi = 0;
    public $nama;
    public $noHp;
    public function __construct($nama, $noHp)
    {
        $this->nama = $nama;
        $this->noHp = $noHp;
    }
    public function getTotalTransaksi()
    {
        return self::$totalTransaksi++;
    }
    public function getNama()
    {
        return $this->nama;
    }
    public function getStatus()
    {
        return "Member";
    }
}


class Pelanggan extends User
{
    public $poin = 0;
    public $transaksi = 0;
    public function tambahPoin()
    {
        $this->poin += floor($this->transaksi / 10000);
    }
    public function getStatus()
    {
        if ($this->poin >= 5) {
            return "Member Premium Banget";
        } elseif ($this->poin >= 3) {
            return "Member Premium";
        } else {
            return "Member";
        }
    }
}


class Layanan
{
    public $jenisLayanan;
    public $jarakTempuh;

    public function __construct()
    {
        $this->jenisLayanan = isset($_POST['jenisLayanan']) ? $_POST['jenisLayanan'] : '';
        $this->jarakTempuh = isset($_POST['jarak']) ? (float)$_POST['jarak'] : 0;
    }
    public function getTarifKM()
    {
        if ($this->jenisLayanan == "GoRide Reguler") {
            return 2500;
        } elseif ($this->jenisLayanan == "GoRide Prioritas") {
            return 3000;
        } elseif ($this->jenisLayanan == "GoCar") {
            return 4500;
        } elseif ($this->jenisLayanan == "GoCar XL") {
            return 6000;
        } elseif ($this->jenisLayanan == "GoFood") {
            return 2000;
        }
    }
    public function getTarif()
    {
        if ($this->jenisLayanan == "GoRide Reguler") {
            return  $this->jarakTempuh * 2500;
        } elseif ($this->jenisLayanan == "GoRide Prioritas") {
            return $this->jarakTempuh * 3000;
        } elseif ($this->jenisLayanan == "GoCar") {
            return $this->jarakTempuh * 4500;
        } elseif ($this->jenisLayanan == "GoCar XL") {
            return $this->jarakTempuh * 6000;
        } elseif ($this->jenisLayanan == "GoFood") {
            return $this->jarakTempuh * 2000;
        }
    }
    public function getJenisLayanan()
    {
        return $this->jenisLayanan;
    }
}


class Voucher
{
    public $kodeVoucher;
    public $diskonPersen = 0;
    public function __construct()
    {
        $this->kodeVoucher = isset($_POST['kodeVoucher']) ? trim($_POST['kodeVoucher']) : '';
    }
    public function hitungDiskonVoucer($subtotal)
    {
        if ($this->kodeVoucher === "HEMAT10") {
            $this->diskonPersen = 0.1;
        } elseif ($this->kodeVoucher === "HEMAT20") {
            $this->diskonPersen = 0.2;
        } elseif ($this->kodeVoucher === "HEMAT30") {
            $this->diskonPersen = 0.3;
        } else {
            $this->diskonPersen = 0;
        }
        return $subtotal * $this->diskonPersen;
    }
    public function getKodeVoucher()
    {
        return $this->kodeVoucher;
    }
}

abstract class Pembayaran
{
    public function getMetode() {}
}

class Transfer extends Pembayaran
{
    public function getMetode()
    {
        return 2500;
    }
}

class Ewallet extends Pembayaran
{
    public function getMetode()
    {
        return 1500;
    }
}

class Cash extends Pembayaran
{
    public function getMetode()
    {
        return 0;
    }
}

class Transaksi
{
    public $pelanggan;
    public $layanan;
    public $pembayaran;
    public $voucher;
    public $jarakTempuh;
    public function hitungSubTotal()
    {
        return $this->jarakTempuh * $this->layanan->getTarifKM();
    }
    public function hitungDiskon()
    {
        $subtotal = $this->hitungSubTotal();
        if ($subtotal > 50000) {
            return $subtotal * 0.05;
        }
        return 0;
    }
    public function hitungBiayaAdmin()
    {
        return $this->pembayaran->getMetode();
    }
    public function hitungTotal()
    {
        $subtotal = $this->hitungSubTotal();
        $voucherDiskon = $this->voucher->hitungDiskonVoucer($subtotal);
        return $subtotal - $this->hitungDiskon() - $voucherDiskon + $this->hitungBiayaAdmin();
    }

    public function tampilStruk()
    {
        $subtotal = $this->hitungSubTotal();
        $voucherDiskon = $this->voucher->hitungDiskonVoucer($subtotal);
        return "<h2>Struktur Pembayaran</h2><br>"
            . "Nama Pelanggan: " . $this->pelanggan->getNama() . "<br>"
            . "Status : " . $this->pelanggan->getStatus() . "<br>_________________________________________<br><br>"
            . "Jenis Layanan: " . $this->layanan->getJenisLayanan() . "<br>"
            . "Jarak Tempuh: " . $this->jarakTempuh . " km<br>"
            . "Tarif/Km: Rp " . number_format($this->layanan->getTarifKM(), 0, ',', '.') . "<br>_________________________________________<br><br>"
            . "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "<br>"
            . "Diskon: Rp " . number_format($this->hitungDiskon(), 0, ',', '.') . "<br>"
            . "Diskon Voucher (" . $this->voucher->getKodeVoucher() . "): Rp " . number_format($voucherDiskon, 0, ',', '.') . "<br>"
            . "Biaya Admin: Rp " . number_format($this->hitungBiayaAdmin(), 0, ',', '.') . "<br><br>_________________________________________<br><br>"
            . "<b>TOTAL BAYAR: Rp </b>" . number_format($this->hitungTotal(), 0, ',', '.') . "<br>"
            . "<em>Selamat anda mendapatkan</em> " . floor($this->hitungTotal() / 10000) . " <em>poin</em>!";
    }
}


if (isset($_POST['submit'])) {
    $pelanggan = new Pelanggan(
        $_POST['nama'],
        $_POST['nohp']
    );

    $layanan = new Layanan();
    $voucher = new Voucher();
    $paymentMap = [
        'Transfer' => Transfer::class,
        'Ewallet' => Ewallet::class,
        'Cash' => Cash::class,
    ];

    $metode = $_POST['metodePembayaran'] ?? 'Cash';
    $pembayaranClass = $paymentMap[$metode] ?? Cash::class;
    $pembayaran = new $pembayaranClass();

    $transaksi = new Transaksi();

    $transaksi->pelanggan = $pelanggan;
    $transaksi->layanan = $layanan;
    $transaksi->pembayaran = $pembayaran;
    $transaksi->voucher = $voucher;
    $transaksi->jarakTempuh = $layanan->jarakTempuh;

    echo "<div class='struk'>";
    echo $transaksi->tampilStruk();
    echo "</div>";
}
?>