<?php
require __DIR__ . "/../connection.php";
if (!isset($_GET["kelas"])) {
  toPage("main");
}
$siswaQuery = $conn->prepare("select * from data_siswa where kelas= :kelas");
$siswaQuery->bindParam(":kelas", $_GET["kelas"]);
$siswaQuery->execute();
if (isset($_GET["bulan"]) && isset($_GET["year"])) {
  $day_length = cal_days_in_month(CAL_GREGORIAN, $_GET["bulan"], $_GET["year"]);
  $absen = $conn->prepare("select * from absen_siswa where MONTH(tanggal_lengkap) = :bulan AND YEAR(tanggal_lengkap) = :tahun");
  $absen->bindParam(":bulan", $_GET["bulan"], PDO::PARAM_STR);
  $absen->bindParam(":tahun", $_GET["year"], PDO::PARAM_STR);
  $absen->execute();
  $dataAbsen = $absen->fetchAll(PDO::FETCH_ASSOC);
} else {
  $day_length = cal_days_in_month(CAL_GREGORIAN, date('m'), date("Y"));
  $absen = $conn->prepare("select * from absen_siswa where MONTH(tanggal_lengkap) = :bulan AND YEAR(tanggal_lengkap) = :tahun");
  $absen->bindValue(":bulan", date('m'));
  $absen->bindValue(":tahun", date("Y"));
  $absen->execute();
  $dataAbsen = $absen->fetchAll(PDO::FETCH_ASSOC);
}
$dataSiswa = $siswaQuery->fetchAll();
$querykelas = $conn->prepare("SELECT kelas from kelas where id_kelas=:id");
$querykelas->bindParam(":id", $_GET["kelas"]);
$querykelas->execute();
$dataKelas = $querykelas->fetch();
?>
<ul class="nav nav-underline sticky-top justify-content-start ps-4">
  <li class="nav-item me-3 fs-3 fw-bold">
    <a class="nav-link <?php
      if (isset($_GET['page'])) {
        if (base64_decode($_GET['page']) == 'main') {
          echo 'active';
        } else {
          echo '';
        }
      }
      ?>" aria-current="page" href="?page=<?= base64_encode('main') ?>&kelas=<?= $_GET['kelas'] ?>">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php
      if (isset($_GET['page'])) {
        if (base64_decode($_GET['page']) == 'siswa') {
          echo 'active';
        } else {
          echo '';
        }
      }
      ?>" aria-current="page" href="?page=<?= base64_encode('siswa') ?>&kelas=<?= $_GET['kelas'] ?>">Siswa</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php
      if (isset($_GET['page'])) {
        if (base64_decode($_GET['page']) == 'absen') {
          echo 'active';
        } else {
          echo '';
        }
      }
      ?>" aria-current="page" href="?page=<?= base64_encode('absen') ?>&kelas=<?= $_GET['kelas'] ?>">Absensi</a>
  </li>
</ul>
<h2 class="ms-4">Data Siswa kelas <?= strtoupper(isset($dataKelas[0]) ? $dataKelas[0] : null) ?></h2>
<div class="container">
  <div class="row">
    <div>
      <div class="btn-group" role="group">
        <button class="btn btn-outline-success"><i style="color: black" data-feather="settings" onclick="option()"></i></button>
        <div class="btn-group toggle-none dropend">
          <button type="button" class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i style="color: black" data-feather="info"></i>
          </button>
          <ul class="dropdown-menu">
            <li class="dropdown-item"><i data-feather="check-circle" width="17" style="color: green;"></i> : HADIR</li>
            <li class="dropdown-item"><i data-feather="alert-circle" width="17" style="color: red;"></i> : SAKIT</li>
            <li class="dropdown-item"><i data-feather="calendar" width="17" style="color: orange;"></i> : IZIN</li>
            <li class="dropdown-item"><i data-feather="slash" width="17" style="color: black;"></i> : Tidak Ada Keterangan (Alpha)</li>
            <li class="dropdown-item"><i data-feather="check-circle" width="17" style="color: red;"></i> : Hari Libur</li>
          </ul>
        </div>
      </div>
      <div id="optionabsensi" class="d-none ms-3 m-3">
        <div class="btn-group">
          <form method="get" class="mt-2 col-3" style="width: 400px">
            <input type="hidden" name="page" value="<?= base64_encode('absen') ?>">
            <input type="hidden" name="kelas" value="<?= $_GET['kelas'] ?>">
            <div class="input-group row">
              <span class="input-group-text">Filter by Tanggal</span>
              <input type="text" class="form-control col-4" placeholder="tahun.." value="<?= date('Y') ?>" maxlength="4" inputmode="numeric" name="year" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
              <select class="form-select input-group-text col-4" aria-label="Default select example" name="bulan" aria-describedby="addon1">
                <?php $args = isset($_GET["bulan"]) ? $_GET["bulan"] : date("m") ?>
                <option value="01" <?= $args == "01" ? 'selected' : "" ?>>Januari</option>
                <option value="03" <?= $args == '02' ? 'selected' : "" ?>>Maret</option>
                <option value="02" <?= $args == '03' ? 'selected' : "" ?>>Februari</option>
                <option value="04" <?= $args == '04' ? 'selected' : "" ?>>April</option>
                <option value="05" <?= $args == '05' ? 'selected' : "" ?>>Mei</option>
                <option value="06" <?= $args == '06' ? 'selected' : "" ?>>Juni</option>
                <option value="07" <?= $args == '07' ? 'selected' : "" ?>>Juli</option>
                <option value="08" <?= $args == '08' ? 'selected' : "" ?>>Agustus</option>
                <option value="09" <?= $args == '09' ? 'selected' : "" ?>>September</option>
                <option value="10" <?= $args == '10' ? 'selected' : "" ?>>Oktober</option>
                <option value="11" <?= $args == '11' ? 'selected' : "" ?>>November</option>
                <option value="12" <?= $args == '12' ? 'selected' : "" ?>>Desember</option>
              </select>
              <button type="submit" class="btn btn-dark col-3" id="addon1"><i data-feather="search"></i></button>
            </div>
          </form>
          <form action="save-xlsx.php" method="get" class="mt-2 col-3" style="width: 400px">
            <input type="hidden" name="page" value="<?= base64_encode('absen') ?>">
            <input type="hidden" name="kelas" value="<?= $_GET['kelas'] ?>">
            <div class="input-group row">
              <span class="input-group-text">Buat File Spreadsheet</span>
              <input type="text" class="form-control col-4" placeholder="tahun.." value="<?= date('Y') ?>" maxlength="4" inputmode="numeric" name="year" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
              <select class="form-select input-group-text col-4" aria-label="Default select example" name="month" aria-describedby="addon1">
                <?php $args = isset($_GET["bulan"]) ? $_GET["bulan"] : date("m") ?>
                <option value="01" <?= $args == "01" ? 'selected' : "" ?>>Januari</option>
                <option value="03" <?= $args == '02' ? 'selected' : "" ?>>Maret</option>
                <option value="02" <?= $args == '03' ? 'selected' : "" ?>>Februari</option>
                <option value="04" <?= $args == '04' ? 'selected' : "" ?>>April</option>
                <option value="05" <?= $args == '05' ? 'selected' : "" ?>>Mei</option>
                <option value="06" <?= $args == '06' ? 'selected' : "" ?>>Juni</option>
                <option value="07" <?= $args == '07' ? 'selected' : "" ?>>Juli</option>
                <option value="08" <?= $args == '08' ? 'selected' : "" ?>>Agustus</option>
                <option value="09" <?= $args == '09' ? 'selected' : "" ?>>September</option>
                <option value="10" <?= $args == '10' ? 'selected' : "" ?>>Oktober</option>
                <option value="11" <?= $args == '11' ? 'selected' : "" ?>>November</option>
                <option value="12" <?= $args == '12' ? 'selected' : "" ?>>Desember</option>
              </select>
              <button type="submit" class="btn btn-primary col-3" id="addon1"><i data-feather="save"></i></button>
            </div>
          </form>
        </div>
      </div>
      <table class="table table-striped mt">
        <thead>
          <tr>
            <th scope="col" rowspan="2" class="text-center border" style="vertical-align: middle">ID</th>
            <th scope="col" rowspan="2" class="text-center border" style="vertical-align: middle">Nama Pegawai</th>
            <th scope="col" colspan="<?= $day_length ?>" class="text-center border">Tanggal</th>
          </tr>
          <tr>
            <?php for ($i = 1; $i <= $day_length; $i++) {
              ?>
              <th scope="col" class="border"><?= $i ?></th>
              <?php
            } ?>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dataSiswa) && $siswaQuery) {
            ?>
            <?php foreach ($dataSiswa as $indexSiswa => $data) {
              $id = $data["id_siswa"];
              ?>
              <tr class="border border-bottom-2">
                <td class="border"><?= $id ?></td>
                <td style="cursor: pointer" class="select border"><?= $data["nama_siswa"] ?></td>
                <?php for ($i = 1; $i <= $day_length; $i++) {
                  $searchAbsen = array_filter($dataAbsen, function($item) use($i, $id) {
                    if (is_array($item) && isset($item['hari_tanggal'])) {
                      return $item['hari_tanggal'] == $i && $item['id_siswa'] == $id;
                    }
                    return false;
                  });
                  $prossedData = array_merge(...$searchAbsen);
                  ?>
                  <?php if ($searchAbsen != false) {
                    if (isset($_GET["bulan"]) && isset($_GET["year"])) {
                      $str = $_GET['year']."-".$_GET["bulan"]."-".$i;
                    } else {
                      $str = date("Y")."-".date("m")."-".$i;
                    }
                    $dat = strtolower(date("D", strtotime($str)));
                    $bool = $dat != "sun" || $dat != "sat";
                    ?>
                    <?php if ($prossedData["status"] == "sakit" && $bool) {
                      ?>
                      <td class="text-center border select" style="vertical-align: middle; cursor: pointer;">
                        <div class="dropup-center dropend">
                          <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="alert-circle" width="17" style="color: red;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <a class="dropdown-item" href="#">
                                <?= !empty($prossedData["keterangan"]) ? $prossedData["keterangan"] : $prossedData["status"] ?>
                              </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center"><a style="width: 100%" class="link-underline-dark link-opacity-50 " href="?page=<?= base64_encode('absen') ?>&id_siswa=<?= $data['id_siswa'] ?>&absen=<?= $prossedData['absensi_id'] ?>&kelas=<?= $_GET['kelas'] ?>">Edit</a></li>
                          </ul>
                        </div>
                      </td>
                      <?php
                    } else if ($prossedData["status"] == "izin" && $bool) {
                      ?>
                      <td class="text-center border select" style="vertical-align: middle; cursor: pointer;">
                        <div class="dropup-center dropend">
                          <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="calendar" width="17" style="color: orange;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <a class="dropdown-item" href="#">
                                <?= !empty($prossedData["keterangan"]) ? $prossedData["keterangan"] : $prossedData["status"] ?>
                              </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center"><a style="width: 100%" class="link-underline-dark link-opacity-50 " href="?page=<?= base64_encode('absen') ?>&id_siswa=<?= $data['id_siswa'] ?>&absen=<?= $prossedData['absensi_id'] ?>&kelas=<?= $_GET['kelas'] ?>">Edit</a></li>
                          </ul>
                        </div>
                      </td>
                      <?php
                    } else if ($prossedData["status"] == "alpha" && $bool) {
                      ?>
                      <td class="text-center border select" style="vertical-align: middle; cursor: pointer;">
                        <div class="dropup-center dropend">
                          <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="slash" width="17" style="color: black;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li>
                              <a class="dropdown-item" href="#">
                                <?= !empty($prossedData["keterangan"]) ? $prossedData["keterangan"] : $prossedData["status"] ?>
                              </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center"><a style="width: 100%" class="link-underline-dark link-opacity-50 " href="?page=<?= base64_encode('absen') ?>&id_siswa=<?= $data['id_siswa'] ?>&absen=<?= $prossedData['absensi_id'] ?>&kelas=<?= $_GET['kelas'] ?>">Edit</a></li>
                          </ul>
                        </div>
                      </td>
                      <?php
                    } else {
                      ?>
                      <td class="text-center border select" style="vertical-align: middle; cursor: pointer;">
                        <div class="dropup-center dropend">
                          <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="check-circle" width="17" style="color: red;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Hari Libur</a></li>
                          </ul>
                        </div>
                      </td>
                      <?php
                    } ?>
                    <?php
                  } else {
                    ?>
                    <td class="text-center border">
                      <?php
                      if (isset($_GET["bulan"]) && isset($_GET["year"])) {
                        $str = $_GET['year']."-".$_GET["bulan"]."-".$i;
                      } else {
                        $str = date("Y")."-".date("m")."-".$i;
                      }
                      $dat = strtolower(date("D", strtotime($str)));
                      if (!($dat == "sun" || $dat == "sat")) {
                        ?>
                        <i data-feather="check-circle" width="17" style="color: green;"></i>
                        <?php
                      } else {
                        ?>
                        <div class="dropup-center dropend">
                          <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="check-circle" width="17" style="color: red;"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Hari Libur</a></li>
                          </ul>
                        </div>
                        <?php
                      } ?>
                    </td>
                    <?php
                  } ?>
                  <?php
                } ?>
              </tr>
              <?php
            } ?>
            <?php
          } else {
            ?>
            <td colspan="<?= 2 + $day_length ?>" class="text-center">Data Tidak Tersedia</td>
            <?php
          } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php if (isset($_GET["id_siswa"]) && !empty($_GET["id_siswa"])) {
  $querySiswaSelect = $conn->prepare("
        SELECT data_siswa.nama_siswa, data_siswa.id_siswa, absen_siswa.*
        FROM data_siswa join absen_siswa on data_siswa.id_siswa = absen_siswa.id_siswa where absen_siswa.id_siswa=:id && absen_siswa.absensi_id = :id_absen
    ");
  $querySiswaSelect->bindParam(":id", $_GET["id_siswa"]);
  $querySiswaSelect->bindParam(":id_absen", $_GET["absen"]);
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
        <form method="post" action="?page=<?= base64_encode("editabsen") ?>" class="" style="width: 100%">
          <div class="input-group">
            <input type="hidden" name="kelas" value="<?= $_GET['kelas'] ?>">
            <input type="hidden" name="siswa" value="<?= $dataSiswaSelect['id_siswa'] ?>">
            <input type="hidden" name="absen" value="
            <?= $dataSiswaSelect['absensi_id'] ?>
            ">
            <span class="input-group-text"><?= $dataSiswaSelect["nama_siswa"] ?></span>
            <select class="form-select" name="status">
              <option value="izin" <?= $dataSiswaSelect["status"] == 'izin' ? 'selected' : '' ?>>Izin</option>
              <option value="alpha" <?= $dataSiswaSelect["status"] == 'alpha' ? 'selected' : '' ?>>Alpha</option>
              <option value="sakit" <?= $dataSiswaSelect["status"] == 'sakit' ? 'selected' : '' ?>>Sakit</option>
            </select>
          </div>
          <div class="input-group">
            <textarea class="form-control" aria-label="With textarea" name="ket" placeholder="Keterangan..."><?= htmlspecialchars($dataSiswaSelect['keterangan']) ?></textarea>
          </div>
          <div class="input-group mt-3">
            <button class="btn btn-success input-group-text" id="basic-addon1">simpan</button>
            <input type="date" class="form-control" name="tanggal" value="<?= isset($dataSiswaSelect['tanggal_lengkap']) ? $dataSiswaSelect['tanggal_lengkap'] : date('Y-m-d') ?>">
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
} ?>