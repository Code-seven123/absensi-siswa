<?php
    require  __DIR__ . "/connection.php";
    require 'vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    function jwtE($ID, $user, $key, $role = "admin"){
        $payload = [
            "iss" => "http://localhost/", // server 
            "aud" => $user, // user
            "iat" => time(), // waktu sekarang
            "nbf" => time(), // waktu yang bisa digunakan
            "exp" => time() + 604800,
            "sub" => $ID, // user id
            "role" => $role // role user
        ];
        return $jwt = JWT::encode($payload, $key, 'HS256');
    }
    function jwtD($jwt, $key) {
      return $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    }
    function jwtV($jwt, $key) {
      global $conn;
      try {
      $jwtd = jwtD($jwt, $key);
      $stmt = $conn->prepare("select * from users where id=:id AND username=:user");
      $stmt->bindParam(":id", $jwtd->sub);
      $stmt->bindParam(":user", $jwtd->aud);
      if($stmt->execute()) {
          $count = $stmt->rowCount();
          $data = $stmt->fetch();
          if($count >= 0) {
            return [
              "status" => true,
              "data" => $data
            ];
          } else {
            return [
              "status" => false,
              "data" => $data
            ];
          }
      } else {
        return [
          "status" => false,
          "data" => null
        ];
      }
      } catch(Exception $e) {
        return [
          "status" => false,
          "data" => null,
          "message" => $e->getMessage()
        ];
      }
    }
    function toPage($page)
    {
        $encBase64 = base64_encode($page);
        echo "<script>window.location.href = '?page=$encBase64'</script>";
    }
    function redirect($url, $delay = 0) {
        if (!headers_sent()) {
            if ($delay > 0) {
                header("Refresh: $delay; url=$url");
            } else {
                header("Location: $url");
            }
            exit();
        } else {
            echo '<script type="text/javascript">';
            if ($delay > 0) {
                echo "setTimeout(function(){ window.location.href = '$url'; }, " . ($delay * 1000) . ");"; // Konversi delay ke milidetik
            } else {
                echo "window.location.href = '$url';";
            }
            echo '</script>';

            echo "<noscript>";
            echo "<meta http-equiv='refresh' content='$delay;url=$url' />";
            echo "</noscript>";
            exit();
        }
    }
