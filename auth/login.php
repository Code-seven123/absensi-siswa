<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require "../connection.php";
require "../utility.php";
$data = jwtV(isset($_SESSION["logindata"]) ? $_SESSION["logindata"] : "", $config["key"]);
if ($data["status"] == true) {
  redirect("..");
}
$usersAdmin = $conn->query("SELECT is_admin FROM users WHERE is_admin='true'");
if($usersAdmin->rowCount() <= 0) {
  $hashPassword = password_hash($config['admin']['password'], PASSWORD_BCRYPT);
  $addAdmin = $conn->prepare("INSERT INTO `users`(`username`, `password`, `is_admin`) VALUES (?, ?, ?)");
  if($addAdmin->execute([$config['admin']['username'], $hashPassword, 'true'])) {
    $msg = "Users admin di buat";
  } else {
    $msg = "Terjadi error saat membuat user admin";
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pass = $_POST["pass"];
  $user = $_POST["user"];
  $searchUsers = $conn->prepare("select * from users where username=:user");
  $searchUsers->bindParam(':user', $user);
  if ($searchUsers->execute()) {
    if ($searchUsers->rowCount()) {
      $data = $searchUsers->fetch();
      if (password_verify($pass, $data["password"])) {
        $is_admin = $data['is_admin'] == 'true' ? "admin" : "user";
        $jwt = jwtE($data["id"], $data["username"], $config["key"], $is_admin);
        $_SESSION["logindata"] = $jwt;

        redirect("..");
      } else {
        $msg = "Password salah!!";
      }
    } else {
      $msg = "Akun tidak ditemukan";
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center" style="width: 100vw; height: 100vh">
    <form action="" class="rounded text-center p-3 d-flex align-items-center flex-column"style="width: 400px; height: 400px; background-color: #ededed" method="post">
      <div>
        <hi class="text-center fs-2 mb-5">Sign Up</hi>
        <?php if (isset($msg)) {
          ?>
          <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($msg) ?>
          </div>
          <?php
        } ?>
        <div class="form-floating mt-5 mb-2">
          <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="user">
          <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pass">
          <label for="floatingPassword">Password</label>
        </div>
        <button type="submit" class="btn btn-outline-warning mt-3" style="width: 100%">Login</button>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
