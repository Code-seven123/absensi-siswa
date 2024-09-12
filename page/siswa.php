<?php 
    require __DIR__ . "/../connection.php";
    if(!isset($_GET["kelas"])){
        toPage("main");
    }
    $querySiswa = $conn->prepare("
        SELECT data_siswa.*, kelas.id_kelas, kelas.kelas
        FROM data_siswa
        JOIN kelas ON data_siswa.kelas = kelas.id_kelas
        WHERE data_siswa.kelas = :id
    ");
    $querySiswa->bindParam(":id", $_GET["kelas"]);
    $querySiswa->execute();
    $dataSiswa = $querySiswa->fetchAll();
    $querykelas = $conn->prepare("SELECT kelas from kelas where id_kelas=:id");
    $querykelas->bindParam(":id", $_GET["kelas"]);
    $querykelas->execute();
    $dataKelas = $querykelas->fetch();
?>
<ul class="nav nav-underline sticky-top justify-content-start ps-4">
    <li class="nav-item me-3 fs-3 fw-bold">
        <a class="nav-link <?php
            if(isset($_GET['page'])) {
                if (base64_decode($_GET['page']) == 'main') {
                    echo 'active';
                } else {
                    echo '';
                }
            }
        ?>" aria-current="page" href="?page=<?= base64_encode('main') ?>">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php
            if(isset($_GET['page'])) {
                if (base64_decode($_GET['page']) == 'siswa') {
                    echo 'active';
                } else {
                    echo '';
                }
            }
        ?>" aria-current="page" href="?page=<?= base64_encode('siswa') ?>">Siswa</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php
            if(isset($_GET['page'])) {
                if (base64_decode($_GET['page']) == 'absen') {
                    echo 'active';
                } else {
                    echo '';
                }
            }
        ?>" aria-current="page" href="?page=<?= base64_encode('absen') ?>&kelas=<?= $_GET['kelas'] ?>">Absensi</a>
    </li>
</ul>
<div class="container">
    <h2>Data Siswa kelas <?= strtoupper(isset($dataKelas[0]) ? $dataKelas[0] : null) ?></h2>
    <div class="btn-group" role="btn-group">
        <a href="?page=<?= base64_encode('tambahsiswa') ?>&kelas=<?= $_GET['kelas'] ?>" class="btn btn-success">Tambah</a>
        <!-- <a href="?page=<?= base64_encode('tambahsiswa') ?>&kelas=<?= $_GET['kelas'] ?>" class="btn btn-danger">Tambah Data by JSON</a> -->
    </div>
    <?php if(isset($_GET["msg"])) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $_GET["msg"] ?>
        </div>
    <?php } ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nis</th>
                <th scope="col">Nama Siswa</th>
                <th scope="col">Kelas</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($dataSiswa) && $querySiswa) { ?>
                <?php foreach ($dataSiswa as $data) {?>
                    <tr>
                        <td scope="row"><?= $data["id_siswa"] ?></td>
                        <td><?= $data["nis"] ?></td>
                        <td><?= $data["nama_siswa"] ?></td>
                        <td><?= $data["kelas"] ?></td>
                        <td><?= $data["jenis_kelamin"] == "laki_laki" ? "Laki Laki" : "Perempuan" ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-warning" href="?page=<?= base64_encode('editsiswa') ?>&id=<?= $data['id_siswa'] ?>&kelas=<?= $_GET['kelas'] ?>">Edit</a>
                                <a class="btn btn-danger" onclick="deleteSiswa(this, `?page=<?= base64_encode('hapussiswa') ?>&id=<?= $data['id_siswa'] ?>&kelas=<?= $_GET['kelas'] ?>`)">Hapus</a>
                                <a href="?page=<?= base64_encode('siswa') ?>&kelas=<?= $_GET['kelas'] ?>&id_siswa=<?= $data['id_siswa'] ?>" class="btn btn-success d-flex justify-content-between align-items-center" style="color: black">Absensi</a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6" class="text-center">Data Tidak Tersedia</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php if(isset($_GET["id_siswa"]) && !empty($_GET["id_siswa"])) {
    $querySiswaSelect = $conn->prepare("
        SELECT nama_siswa, id_siswa
        FROM data_siswa where id_siswa=:id
    "); 
    $querySiswaSelect->bindParam(":id", $_GET["id_siswa"]);
    $querySiswaSelect->execute();
    $dataSiswaSelect = $querySiswaSelect->fetch();
?>
    <div class="popup">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4>Absensi</h4>
                    <button class="btn btn-outline-secondar" onclick="closePopup()"><i data-feather="x"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="?page=<?= base64_encode("tambahabsen") ?>" class="" style="width: 100%">
                    <div class="input-group">
                        <input type="hidden" name="kelas" value="<?= $_GET['kelas'] ?>">
                        <input type="hidden" name="siswa" value="<?= $dataSiswaSelect["id_siswa"] ?>">
                        <span class="input-group-text"><?= $dataSiswaSelect["nama_siswa"] ?></span>
                        <select class="form-select" name="status">
                            <option value="izin">Izin</option>
                            <option value="alpha">Alpha</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <textarea class="form-control" aria-label="With textarea" name="ket" placeholder="Keterangan..."></textarea>
                    </div>
                    <div class="input-group mt-3">
                        <button class="btn btn-success input-group-text" id="basic-addon1">simpan</button>
                        <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
