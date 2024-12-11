<?php
require __DIR__ . "/../connection.php";
if (!isset($_GET["kelas"]) || !isset($_GET["id"])) {
  toPage("main");
}
$id = $_GET['id'];
$kelas = $_GET["kelas"];

$query = $conn->prepare("UPDATE `data_siswa` SET is_deleted='true' WHERE id_siswa=:id");
$query->bindParam(":id", $id);
if ($query->execute()) {
  redirect("?page=".base64_encode("siswa")."&kelas=".$kelas);
}