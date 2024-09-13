<?php
session_start();
require __DIR__ . "/utility.php";
date_default_timezone_set("Asia/jakarta");
setlocale(LC_TIME, "id_ID.UTF-8");
if (!isset($_GET["page"])) {
  toPage("main");
  exit();
}
$data = jwtV(isset($_SESSION["logindata"]) ? $_SESSION["logindata"] : "", $config["key"]);
if ($data["status"] == false) {
  redirect("auth/login.php");
}
function selectPage() {
  global $config;
  global $data;
  $getParam = isset($_GET["page"]) ? $_GET["page"] : null;
  $encParam = base64_decode($getParam);
  switch ($encParam) {
    case 'siswa':
      if ($data["status"] != true) break;
    include "page/siswa.php";
    break;
    case 'tambahabsen':
      if ($data["status"] != true) break;
    include "page/absen-add.php";
    break;
    case 'editsiswa':
      if ($data["status"] != true) break;
    include "page/siswa-edit.php";
    break;
    case 'tambahsiswa':
      if ($data["status"] != true) break;
    include "page/siswa-add.php";
    break;
    case 'absen':
      if ($data["status"] != true) break;
    include "page/absensi.php";
    break;
    case 'hapussiswa':
      if ($data["status"] != true) break;
    include "page/hapusiswa.php";
    break;
    case 'editabsen':
      if ($data["status"] != true) break;
    include "page/absen-edit.php";
    break;
    default:
      include "page/main.php";
      break;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Siswa SMKN 1 MAJA</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    .toggle-noe .dropdown-toggle::after {
      display: none; /* Hapus panah */
    }
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    }
    .dropdown:hover .dropdown-menu {
    display: block;
    }
    .popup{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100vw;
    height: 100vh;
    z-index: 99999;
    top: 0;
    left: 0;
    position: fixed;
    }
  </style>
</head>
<body>
  <?php selectPage() ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    feather.replace()
    function deleteSiswa(event, url){
    Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
    Swal.fire({
    title: "Deleted!",
    text: "Your file has been deleted.",
    icon: "success"
    });
    window.location.href = url;
    } else {
    return false
    }
    })
    }
    function validateNumeric(e){
    e.value = e.value.replace(/[^0-9]/g, '')
    }
    function closePopup(){
    let url = new URL(window.location.href)
    url.searchParams.delete("id_siswa")
    window.location.href = url.toString();
    }
    function option() {
      const opsi = document.getElementById("optionabsensi")
      opsi.classList.toggle("d-none")
    }
  </script>

</body>

</html>