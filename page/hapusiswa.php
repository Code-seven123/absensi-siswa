<?php
require __DIR__ . "/../connection.php";
if (!isset($_GET["kelas"]) || !isset($_GET["id"])) {
  toPage("main");
}
$id = $_GET['id'];
$kelas = $_GET["kelas"];

$query = $conn->prepare("DELETE FROM `data_siswa` WHERE id_siswa=:id");
$query->bindParam(":id", $id);
if ($query->execute()) {
  redirect("?page=".base64_encode("siswa")."&kelas=".$kelas);
}