<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: #94B49F;
            font-family: 'Poppins', sans-serif;
        }

        h1{
            color:#94B49F;
            font-weight: bold;
            text-align: center;
        }

        .container {

        }

        .card{
            background: #FFFDE3;
        }
        select{
            width: 198px;
        }

        .hasil{
            color:#94B49F;
        }
    </style>
    <title>Rental Motor</title>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center">
    <div class="card p-5">
        <center>
            <h1>Rental Motor</h1>
            <br>
            <form action="" method="post">
                <table>
                    <tr>
                        <td>Nama Pelanggan: &nbsp;</td>
                        <td>
                            <input type="text" name="nama" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Lama Waktu Rental (per hari): &nbsp;</td>
                        <td>
                            <input type="number" name="jumlah" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Motor: &nbsp;</td>
                        <td>
                            <select name="jenis" class="form-control" required>
                                <option hidden>Pilih Motor</option>
                                <option value="Vesmet">Vesmet</option>
                                <option value="Vario">vario</option>
                                <option value="Aerox">Aerox</option>
                                <option value="Fazzio">Fazzio</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><br><input type="submit" class="btn btn-lg btn-block btn-success" value="Sewa" name="kirim"></td>
                    </tr>
                </table>
            </form>
            <br>
            <div class="hasil">
            <?php
                class motor {
                    protected $ppn;
                    private $Vesmet, $Vario, $Aerox, $Fazzio;
                    private $isMember = ['baim', 'ervan', 'rizqi', 'salmon']; // Properti untuk menandai status anggota
                    public $jumlah;
                    public $jenis;
                    public $memberName;

                    public function __construct() {
                        $this->ppn = 0.1; // Mengubah PPN menjadi 10%
                    }

                    public function setHarga($tipe1, $tipe2, $tipe3, $tipe4) {
                        $this->Vesmet = $tipe1;
                        $this->Vario = $tipe2;
                        $this->Aerox = $tipe3;
                        $this->Fazzio = $tipe4;
                    }

                    public function getMotor() {
                        $data["Vesmet"] = $this->Vesmet;
                        $data["Vario"] = $this->Vario;
                        $data["Aerox"] = $this->Aerox;
                        $data["Fazzio"] = $this->Fazzio;
                        return $data;
                    }

                    public function isMember() {
                        return in_array($this->memberName, $this->isMember);
                    }

                    public function addMember($name) {
                        if (!in_array($name, $this->isMember)) {
                            $this->isMember[] = $name;
                        }
                    }

                    public function removeMember($name) {
                        if (($key = array_search($name, $this->isMember)) !== false) {
                            unset($this->isMember[$key]);
                        }
                    }

                    public function getMembers() {
                        return $this->isMember;
                    }
                }

                class Sewa extends motor {
                    private $diskon = 0; // Menetapkan default diskon ke 0

                    public function setDiskon($diskon) {
                        $this->diskon = $diskon;
                    }

                    public function hargaSewa() {
                        $dataSewa = $this->getMotor();
                        $hargaSewa = $this->jumlah * $dataSewa[$this->jenis];
                        $diskon = 0;

                        if ($this->isMember()) {
                            $diskon = $hargaSewa * ($this->diskon / 100); // Menghitung diskon berdasarkan persentase diskon
                        }

                        $hargaSetelahDiskon = $hargaSewa - $diskon;
                        $hargaPPN = $hargaSetelahDiskon * $this->ppn;
                        $hargaBayar = $hargaSetelahDiskon + $hargaPPN;
                        return $hargaBayar;
                    }

                    public function cetakPembelian() {
                        $dataSewa = $this->getMotor();
                        echo "-----------------------------------------------------" . "<br>";
                        if ($this->isMember()) {
                            echo "Anda berstatus sebagai Member mendapatkan diskon sebesar " . $this->diskon . "%<br>";
                        } else {
                            echo "Anda bukan member, tidak mendapatkan diskon<br>";
                        }
                        echo "Jenis motor yang dirental adalah : " . $this->jenis . "<br>";
                        echo "Harga rental motor per-harinya " . $dataSewa[$this->jenis] . "<br>";
                        echo "Total yang harus anda bayar Rp. " . $this->hargaSewa() . "<br>";
                        echo "-----------------------------------------------------" . "<br>";
                    }
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $sewa = new Sewa();
                    $sewa->setHarga(50000, 70000, 60000, 40000); // Harga per hari
                    $sewa->setDiskon(10); // Diskon 10%
                    $sewa->jumlah = $_POST['jumlah']; // Jumlah hari sewa
                    $sewa->jenis = $_POST['jenis']; // Jenis motor yang disewa
                    $sewa->memberName = $_POST['nama']; // Nama member yang melakukan sewa
                    $sewa->cetakPembelian();
                }
            ?>
            </div>
        </center>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>
</html>