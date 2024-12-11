<?php
require __DIR__ . "/../connection.php";
if (!isset($_GET["kelas"])) {
  toPage("main");
}
if(isset($_GET['id'])) {
  if($_GET['action'] == 'recovery') {
    $recoverySiswa = $conn->prepare("UPDATE `data_siswa` SET is_deleted='false' where kelas=? AND id_siswa=?");
    $recoverySiswa->bindValue(1, $_GET["kelas"]);
    $recoverySiswa->bindValue(2, $_GET["id"]);
    if($recoverySiswa->execute()) {
      $_GET["msg"] = "Sukses memulihkan sampah";
    }
  } else if($_GET['action'] == 'delete') {
    if($_GET['id'] == 'null'){
      $deleteSiswaAll = $conn->prepare("DELETE FROM `data_siswa` where kelas=? AND is_deleted='true'");
      $deleteSiswaAll->bindValue(1, $_GET["kelas"]);
      if($deleteSiswaAll->execute()) {
        $_GET["msg"] = "Sukses menghapus semua sampah";
      }
    } else if(isset($_GET['id'])) {
      $deleteSiswaAsId = $conn->prepare("DELETE FROM `data_siswa` where kelas=:kelas AND is_deleted='true' AND id_siswa=:id");
      $deleteSiswaAsId->bindValue(":kelas", $_GET["kelas"]);
      $deleteSiswaAsId->bindValue(":id", $_GET["id"]);
      if($deleteSiswaAsId->execute()) {
        $_GET["msg"] = "Sukses menghapus sampah";
      }
    }
  }
}
$querySiswa = $conn->prepare("
        SELECT data_siswa.*, kelas.id_kelas, kelas.kelas
        FROM data_siswa
        JOIN kelas ON data_siswa.kelas = kelas.id_kelas
        WHERE data_siswa.kelas = :id AND data_siswa.is_deleted = 'true'
    ");
$querySiswa->bindParam(":id", $_GET["kelas"]);
$querySiswa->execute();
$dataSiswa = $querySiswa->fetchAll();
$querykelas = $conn->prepare("SELECT kelas from kelas where id_kelas=:id");
$querykelas->bindParam(":id", $_GET["kelas"]);
$querykelas->execute();
$dataKelas = $querykelas->fetch();
?>

<h2 class="ms-4">Sampah kelas <?= htmlspecialchars(strtoupper(isset($dataKelas[0]) ? $dataKelas[0] : null)) ?></h2>
<div class="container">
  <div class="btn-group" role="btn-group">
    <a class="btn btn-outline-warning col-1" onclick="history.back()"><img style="width: 1rem" src="https://www.svgrepo.com/show/533593/arrow-left.svg" alt=""></a>
    <a class="btn btn-danger col-11 d-flex justify-content-center align-items-center" onclick="deleteSiswa(this, `?page=<?= base64_encode('trashsiswa') ?>&id=null&kelas=<?= $_GET['kelas'] ?>&action=delete`)">Hapus Permanen Semua Data</a>
  </div>
  <?php if (isset($_GET["msg"])) {
    ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($_GET["msg"]) ?>
    </div>
    <?php
  } ?>
  <table class="table siswa">
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
      <?php if (!empty($dataSiswa) && $querySiswa) {
        ?>
        <?php foreach ($dataSiswa as $data) {
          ?>
          <tr>
            <td scope="row"><?= htmlspecialchars($data["id_siswa"]) ?></td>
            <td><?= htmlspecialchars($data["nis"]) ?></td>
            <td><?= htmlspecialchars(kapital($data["nama_siswa"])) ?></td>
            <td><?= htmlspecialchars(strtoupper($data["kelas"])) ?></td>
            <td><?= htmlspecialchars($data["jenis_kelamin"] == "laki_laki" ? "Laki Laki" : "Perempuan") ?></td>
            <td>
              <div class="btn-group" role="group">
                <a class="btn btn-danger" onclick="deleteSiswa(this, `?page=<?= base64_encode('trashsiswa') ?>&id=<?= $data['id_siswa'] ?>&kelas=<?= $_GET['kelas'] ?>&action=delete`)">Hapus Permanen</a>
                <a href="?page=<?= base64_encode('trashsiswa') ?>&kelas=<?= $_GET['kelas'] ?>&id=<?= $data['id_siswa'] ?>&action=recovery" class="btn btn-success d-flex justify-content-between align-items-center" style="color: black">Pulihkan Data</a>
              </div>
            </td>
          </tr>
          <?php
        } ?>
        <?php
      } else {
        ?>
        <tr>
          <td colspan="6" class="text-center">Data Tidak Tersedia</td>
        </tr>
        <?php
      } ?>
    </tbody>
  </table>
</div>