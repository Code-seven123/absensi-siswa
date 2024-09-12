<?php
  session_start();
  require "vendor/autoload.php";
  require "utility.php";
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  
  $data = jwtV(isset($_SESSION["logindata"]) ? $_SESSION["logindata"] : "", $config["key"]);
  if($data["status"] == false) {
    redirect("auth/login.php");
  }
  $json = file_get_contents(__DIR__ . "/config.json");
    $config = json_decode($json, true);
  try {
    $host = $config['mysql']['host'];
    $db = $config["mysql"]["database"];
    $dsn = "mysql:host=$host;dbname=$db";
    $conn = new PDO(
        $dsn,
        $config["mysql"]["user"],
        $config["mysql"]["password"]
    );
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch (Exception $e) {
}
  $tahun = isset($_POST["year"]) ? $_POST["year"] : date("Y");
  $bulan = isset($_POST["month"]) ? $_POST["month"] : date("m");
  $kelas = isset($_POST["kelas"]) ? $_POST["kelas"] : 3;
  $day_length = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
  $querySiswa = $conn->prepare("
    select
    nama_siswa,
    kelas,
    id_siswa,
    nis
    from data_siswa
    where kelas=:kelas
  ");
  $querySiswa->bindParam(":kelas", $kelas);
  $querySiswa->execute();
  $dataSiswa = $querySiswa->fetchAll();
  $querykelas = $conn->prepare("SELECT kelas from kelas where id_kelas=:id");
  $querykelas->bindParam(":id", $kelas);
  $querykelas->execute();
  $dataKelas = $querykelas->fetch();

  $proccedData = [];
  foreach ($dataSiswa as $value) {
    $statusArray = [];
    for($i = 1; $i <= $day_length;$i++) {
      $stmt = $conn->prepare("
        select
        absen_siswa.*
        FROM absen_siswa
        where
        YEAR(absen_siswa.tanggal_lengkap) = :year AND
        MONTH(absen_siswa.tanggal_lengkap) = :month AND
        id_siswa = :id
      ");
      $stmt->bindParam(":year", $tahun);
      $stmt->bindParam(":month", $bulan);
      $stmt->bindParam(":id", $value["id_siswa"]);
      $stmt->execute();
      $dataAbsen = $stmt->fetchAll();
      $searchAbsen = array_filter($dataAbsen, function($item) use($i, $value) {
        if (is_array($item) && isset($item['hari_tanggal'])) {
          return $item['hari_tanggal'] == $i && $item['id_siswa'] == $value["id_siswa"];
        }
        return false;
      });
      $prossedData = array_merge(...$searchAbsen);
      $str = $tahun."-".$bulan."-".$i;
      $dat = strtolower(date("l", strtotime($str)));
      if($dat != "sunday" && $dat != "saturday") {
        $status = empty($prossedData["status"]) ? 'Hadir' : $prossedData["status"];
      } else {
        $status = "Hari Libur";
      }
      $statusArray[] = [
        $status
      ];
    }
    $status = array_merge(...$statusArray);
    $proccedData[] = [
      "id" => $value["id_siswa"],
      "nis" => $value["nis"],
      "nama" => $value["nama_siswa"],
      "kelas" => $dataKelas["kelas"],
      "status" => $status
    ];
  }

  $alphabet = [
    'A', 'B', 'C', 'D', 'E', 'F',
    'G', 'H', 'I', 'J', 'K', 'L',
    'M', 'N', 'O', 'P', 'Q', 'R',
    'S', 'T', 'U', 'V', 'W', 'X',
    'Y', 'Z'
  ];

  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  $sheet->setCellValue('A1', ' ');
  $sheet->setCellValue('B1', ' ');
  $sheet->setCellValue('C1', ' ');
  $sheet->setCellValue('D1', ' ');
  $sheet->setCellValue('E1', 'Tanggal');
  
  $sheet->setCellValue('A2', 'ID');
  $sheet->setCellValue('B2', 'NIS');
  $sheet->setCellValue('C2', 'Nama Siswa');
  $sheet->setCellValue('D2', 'Kelas');
  $keyArray = [];
  for($i = 3; $i < $day_length + 3; $i++) {
    $key = isset($alphabet[$i]) ? $alphabet[$i] : 0;
    if($key == 0) {
      $n = $i - 26;
      $newKey = isset($alphabet[$n]) ? $alphabet[$n] : 0;
      $keyArray[] = "A".$newKey;
    } else {
      $keyArray[] = $key;
    }
  }
  foreach($keyArray as $index => $cell) {
    $key = $cell."2";
    $sheet->setCellValue($key, $index + 1);
  }
  $row = 3;
  foreach ($proccedData as $item) {
    $sheet->setCellValue('A' . $row, $item["id"]);
    $sheet->setCellValue('B' . $row, $item["nis"]);
    $sheet->setCellValue('C' . $row, $item["nama"]);
    $sheet->setCellValue('D' . $row, $item["kelas"]);
    foreach($keyArray as $index => $cell) {
      $key = $cell.$row;
      $sheet->setCellValue($key, $item["status"][$index]);
    }
    $row++;
  }
  $writer = new Xlsx($spreadsheet);
  $file = "absensi_".$tahun."-".$bulan."_kelas ".$dataKelas["kelas"].".xlsx";
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="'.$file.'"');
  header('Cache-Control: max-age=0');
  $writer->save('php://output');
  close();
