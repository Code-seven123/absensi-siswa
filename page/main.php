<?php
    require __DIR__ . "/../connection.php";
?>
<div class="container">
    <ul class="list-group mt-5">
        <?php
            $queryKelas = $conn->query("select * from kelas");
            $dataKelas = $queryKelas->fetchAll();
            $page = base64_decode($_GET['page']);
            foreach ($dataKelas as $value) {
                $queryString = http_build_query([
                    "page" => base64_encode("siswa"),
                    "kelas" => htmlspecialchars($value['id_kelas'])
                ]);
                $querykelas = $conn->prepare("SELECT id_siswa from data_siswa where kelas=:id");
                $querykelas->bindParam(":id", $value["id_kelas"]);
                $querykelas->execute();
                $dataKelas = $querykelas->rowCount();
        ?>
            <li class="list-group-item">
                <a href="?<?= $queryString ?>" class=" d-flex justify-content-between align-items-center list-group-item list-group-item-action <?php
                    if(isset($_GET['id'])) {
                        if ($_GET['id'] == $value['id_kelas']) {
                            echo 'active';
                        } else {
                            echo '';
                        }
                        
                    }
                ?>" aria-current="true">
                    <?= htmlspecialchars($value["kelas"]) ?>
                    
                    <span class="badge text-bg-primary rounded-pill"><?= $dataKelas ?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>