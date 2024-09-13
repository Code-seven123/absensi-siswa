<?php
require __DIR__ . "/../connection.php";
$queryKelas = $conn->query("select * from kelas");
$dataKelas = $queryKelas->fetchAll();
$jurusan = [];
foreach ($dataKelas as $value) {
  if (isset($value["jurusan"])) {
    $jurusan[] = $value["jurusan"];
  }
}
$uniqueJurusan = array_unique($jurusan);
?>
<ul class="nav nav-tabs justify-content-between">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#">Absensi Siswa</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="auth/logout.php">Logout</a>
  </li>
</ul>
<div class="container mt-4">
  <div class="accordion" id="accordionExample">
    <?php foreach ($uniqueJurusan as $index => $valueJurusan) {
      ?>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordion<?= $index ?>" aria-expanded="false" aria-controls="accordion<?= $index ?>">
            <?= kapital($valueJurusan) ?>
          </button>
        </h2>
        <div id="accordion<?= $index ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <ul class="list-group list-group-flush">
              <?php
              $filtered = array_filter($dataKelas, function ($item) use($valueJurusan) {
                if ($item["jurusan"] == $valueJurusan) {
                  return true;
                } else {
                  return false;
                }
              });
              foreach ($filtered as $kelas) {
                $param = http_build_query([
                  "page" => base64_encode("absen"),
                  "kelas" => $kelas["id_kelas"]
                ]);
                $querySiswa = $conn->prepare("
                      SELECT id_siswa from data_siswa where kelas=:id
                    ");
                $querySiswa->bindParam(":id",
                  $kelas["id_kelas"]);
                $querySiswa->execute();
                $length = $querySiswa->rowCount();
                ?>
                <li class="list-group-item">
                  <a style="color: black; text-decoration: none; width: 100%" class="d-flex justify-content-between align-items-center" href="?<?= $param ?>">
                    <?= htmlspecialchars(strtoupper($kelas["kelas"])) ?>
                    <span class="badge text-bg-primary rounded-pill"><?= htmlspecialchars($length) ?></span>
                  </a>
                </li>
                <?php
              } ?>
            </ul>
          </div>
        </div>
      </div>
      <?php
    } ?>
  </div>
</div>