
<?php
class Buku {
    private $isbn;
    private $judul;
    private $penulis;
    private $status = "Tersedia";

    public function __construct($isbn, $judul, $penulis) {
        $this->isbn = $isbn;
        $this->judul = $judul;
        $this->penulis = $penulis;
    }

    public function getJudul() {
        return $this->judul;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function infoBuku() {
        return "[$this->isbn] $this->judul - $this->penulis ($this->status)";
    }
}

class Anggota {
    private $nama;
    private $peran;
    private $maksBuku;
    private $jumlah = 0;

    public function __construct($nama, $peran, $maksBuku) {
        $this->nama = $nama;
        $this->peran = $peran;
        $this->maksBuku = $maksBuku;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getMaksBuku() {
        return $this->maksBuku;
    }

    public function getJumlah() {
        return $this->jumlah;
    }

    public function tambahPinjam() {
        $this->jumlah++;
    }

    public function kurangPinjam() {
        if ($this->jumlah > 0) {
            $this->jumlah--;
        }
    }

    public function infoAnggota() {
        return "$this->nama - $this->peran (maks $this->maksBuku buku)";
    }
}

class Mahasiswa extends Anggota {
    public function __construct($nama, $prodi) {
        parent::__construct($nama, "Mahasiswa ($prodi)", 3);
    }
}

class Dosen extends Anggota {
    public function __construct($nama, $nidn) {
        parent::__construct($nama, "Dosen (NIDN $nidn)", 5);
    }
}

class Peminjaman {
    private $anggota;
    private $buku;
    private $tglPinjam;
    private $tglTempo;
    private $status = false;

    public function __construct($anggota, $buku, $tglPinjam, $tglTempo) {
        $this->anggota = $anggota;
        $this->buku = $buku;
        $this->tglPinjam = $tglPinjam;
        $this->tglTempo = $tglTempo;
    }

    public function prosesPinjam() {
        if ($this->status == true) {
            echo "Buku ini masih dipinjam.<br>";
            return;
        }

        if ($this->buku->getStatus() != "Tersedia") {
            echo "Buku " . $this->buku->getJudul() . " sedang dipinjam.<br>";
            return;
        }

        if ($this->anggota->getJumlah() >= $this->anggota->getMaksBuku()) {
            echo "Batas peminjaman sudah tercapai.<br>";
            return;
        }

        $this->buku->setStatus("Dipinjam");
        $this->anggota->tambahPinjam();
        $this->status = true;

        echo $this->anggota->getNama() . " meminjam buku ";
        echo $this->buku->getJudul();
        echo " | Pinjam: $this->tglPinjam";
        echo " | Tempo: $this->tglTempo";
        echo " | Status: Sedang dipinjam.<br>";
    }

    public function prosesKembali($hariTerlambat, $dendaPerHari) {
        if ($this->status == false) {
            echo "Buku belum dipinjam atau sudah dikembalikan.<br>";
            return;
        }

        $this->buku->setStatus("Tersedia");
        $this->anggota->kurangPinjam();
        $this->status = false;

        if ($hariTerlambat < 0) {
            $hariTerlambat = 0;
        }

        $denda = $hariTerlambat * $dendaPerHari;

        echo $this->anggota->getNama() . " mengembalikan buku ";
        echo $this->buku->getJudul();
        echo " | Status: Sudah kembali";
        echo " | Denda: Rp " . number_format($denda, 0, ',', '.');
        echo "<br>";
    }
}

echo "Perpustakaan<br><br>";

echo "Data Anggota<br>";

$mhs = new Mahasiswa("Asep", "Sistem Informasi");
echo $mhs->infoAnggota() . "<br>";

$dosen = new Dosen("Pak Yanto", "0012345678");
echo $dosen->infoAnggota() . "<br><br>";

echo "Data Buku<br>";

$buku1 = new Buku("978-1", "SKD KEDINASAN 2026 - Winkay", "Winkay");
echo $buku1->infoBuku() . "<br><br>";

echo "Peminjaman<br>";

$pinjam1 = new Peminjaman($mhs, $buku1, "01-10-2026", "08-10-2026");
$pinjam1->prosesPinjam();

echo "Status buku: " . $buku1->getStatus() . "<br><br>";

echo "Coba pinjam buku yang sama<br>";

$pinjam2 = new Peminjaman($dosen, $buku1, "02-10-2026", "09-10-2026");
$pinjam2->prosesPinjam();

echo "<br>Pengembalian<br>";

$pinjam1->prosesKembali(5, 500);

echo "Status buku: " . $buku1->getStatus() . "<br><br>";

echo "Coba kembalikan lagi<br>";

$pinjam1->prosesKembali(5, 500);
?>
