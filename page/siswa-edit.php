<?php
    require __DIR__ . "/../connection.php";
    if(!isset($_GET["kelas"])){
        toPage("main");
    }
    $querykelas = $conn->prepare("SELECT kelas from kelas where id_kelas=:id");
    $querykelas->bindParam(":id", $_GET["kelas"]);
    $querykelas->execute();
    $dataKelas = $querykelas->fetch();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $nis = $_POST["nis"];
            $nama = $_POST["nama"];
            $jk = $_POST["jk"];
            $query = $conn->prepare("INSERT INTO `data_siswa` (`nis`, `nama_siswa`, `kelas`, `jenis_kelamin`) VALUES (:nis, :nama, :kelas, :jk)");
            $query = $conn->prepare("UPDATE `data_siswa` SET `nis`=:nis,`nama_siswa`=:nama,`kelas`=:kelas,`jenis_kelamin`=:jk WHERE id_siswa=:id");
            $query->bindParam(":nis", $nis);
            $query->bindParam(":nama", $nama);
            $query->bindParam(":jk", $jk);
            $query->bindParam(":kelas", $_GET["kelas"]);
            $query->bindParam(":id", $_GET["id"]);
            if($query->execute()){
                $param = http_build_query([
                    "page" => base64_encode('siswa'),
                    "kelas" => $_GET["kelas"]
                ]);
                redirect("?".$param);
            } else {
                $param = http_build_query([
                    "page" => base64_encode('siswa'),
                    "kelas" => $_GET["kelas"]
                ]);
                redirect("?".$param);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            $param = http_build_query([
                "page" => base64_encode('siswa'),
                "kelas" => $_GET["kelas"]
            ]);
            redirect("?".$param);
        }
    } else {
        $stmt = $conn->prepare("select * from data_siswa where id_siswa = :id");
        $stmt->bindParam(":id", $_GET["id"]);
        $stmt->execute();
        $data = $stmt->fetch();
    }
?>
<div class="container">
    <h2>Halaman Siswa kelas <?= strtoupper(isset($dataKelas[0]) ? $dataKelas[0] : null) ?></h2>
    <form method="post" class="mt-4">
        <div class="row mb-3">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Nomor Induk Siswa (NIS)</label>
            <div class="col-sm-10">
            <input type="text" value="<?= $data['nis'] ?>" name="nis" oninput="validateNumeric(this)" pattern="[0-9]*" inputmode="numeric" maxlength="6" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Nama Siswa</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" id="inputPassword3" name="nama" value="<?= $data['nama_siswa'] ?>" >
            </div>
        </div>
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Jenis Kelamin</legend>
            <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jk" id="gridRadios1" value="laki_laki" <?= $data['jenis_kelamin'] == 'laki_laki' ? 'checked' : '' ?>>
                <label class="form-check-label" for="gridRadios1">
                    Laki Laki
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jk" id="gridRadios2" value="perempuan" <?= $data['jenis_kelamin'] != 'laki_laki' ? 'checked' : '' ?>>
                <label class="form-check-label" for="gridRadios2">
                    Perempuan
                </label>
            </div>
        </fieldset>
         <div class="btn-group">
        <button type="submit" class="btn btn-primary">Simpan</button>

        <a href="?page=<?= base64_encode('siswa') ?>&kelas=<?= $_GET['kelas'] ?>" class="btn btn-success">kembali</a>
      </div>
    </form>
</div>
