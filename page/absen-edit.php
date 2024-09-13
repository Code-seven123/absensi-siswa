<?php
# Array ( [kelas] => 2 [siswa] => 5 [absen] => 14 [status] => alpha [ket] => [tanggal] => 2024-09-11 )
require __DIR__ . "/../connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $siswa_id = $_POST["siswa"];
  $ket = $_POST["ket"];
  $status = $_POST["status"];
  $date = strtotime($_POST['tanggal']);
  $id = $_POST['absen'];
  try {
    $stmt = $conn->prepare("UPDATE `absen_siswa` SET `hari_tanggal`=:day,`tanggal_lengkap`=:date,`id_siswa`=:siswa,`status`=:status,`keterangan`=:ket WHERE absensi_id=:id");
    $day = date('d', $date);
    $date = date("Y-m-d", $date);
    $stmt->bindParam(':day', $day);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':siswa', $siswa_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':ket', $ket);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
      $param = http_build_query([
        "page" => base64_encode('absen'),
        "kelas" => $_POST["kelas"]
      ]);
    } else {
      $param = http_build_query([
        "page" => base64_encode('siswa'),
        "kelas" => $_POST["kelas"]
      ]);
    }
    redirect("?".$param);
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    $param = http_build_query([
      "page" => base64_encode('absen'),
      "kelas" => $_POST["kelas"]
    ]);
    redirect("?".$param);
  }
}
?>