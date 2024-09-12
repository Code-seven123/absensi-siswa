<?php 
    require __DIR__ . "/../connection.php";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $siswa_id = $_POST["siswa"];
        $ket = $_POST["ket"];
        $status = $_POST["status"];
        $date = strtotime($_POST['tanggal']);
        try {
            $dateAbsen = date('Y-m-d', $date);
            $queryAbsen = $conn->prepare("SELECT status from absen_siswa where id_siswa=:id and tanggal_lengkap=:date");
            $queryAbsen->bindParam(":id", $siswa_id);
            $queryAbsen->bindParam(":date", $dateAbsen);
            $queryAbsen->execute();
            $lengthAbsen = $queryAbsen->rowCount();
            $dataAbsen = $queryAbsen->fetch();
            if($lengthAbsen <= 0) {
                $stmt = $conn->prepare("INSERT INTO absen_siswa (hari_tanggal, tanggal_lengkap, id_siswa, status, keterangan) VALUES (:day, :date, :siswa, :status, :ket)");
                $day = date('d', $date);
                $date = date("Y-m-d", $date);
                $stmt->bindParam(':day', $day);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':siswa', $siswa_id);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':ket', $ket);
                if($stmt->execute()){
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
            } else {
                $param = http_build_query([
                    "page" => base64_encode('siswa'),
                    "kelas" => $_POST["kelas"],
                    "msg" => "Data sudah ada di database dengan status ".$dataAbsen["status"]
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
