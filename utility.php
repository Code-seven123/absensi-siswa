<?php
require __DIR__ . "/connection.php";
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
function jwtE($ID, $user, $key, $role = "admin") {
  $payload = [
    "iss" => "http://localhost/",
    // server
    "aud" => $user,
    // user
    "iat" => time(),
    // waktu sekarang
    "nbf" => time(),
    // waktu yang bisa digunakan
    "exp" => time() + 604800,
    "sub" => $ID,
    // user id
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
    if ($stmt->execute()) {
      $count = $stmt->rowCount();
      $data = $stmt->fetch();
      if ($count >= 0) {
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
function toPage($page) {
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
function kapital($text) {
  // Daftar kata yang tidak perlu dikapitalisasi (kecuali di awal kalimat)
  $kata_kecil = [
    'dan',
    'atau',
    'tetapi',
    'dengan',
    'serta',
    'ke',
    'di',
    'dari',
    'pada',
    'untuk',
    'oleh',
    'yang',
    'karena',
    'bagi',
    'seperti',
    'dalam',
    'atas',
    'ke',
    'dengan'
  ];

  // Pisahkan kalimat berdasarkan tanda titik
  $kalimat_array = preg_split('/([.?!])\s*/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

  // Variabel untuk hasil akhir
  $hasil = '';

  // Loop setiap bagian dari teks yang sudah dipisahkan
  for ($i = 0; $i < count($kalimat_array); $i += 2) {
    if (isset($kalimat_array[$i])) {
      $kalimat = trim($kalimat_array[$i]);
      if (!empty($kalimat)) {
        // Pecah kalimat menjadi kata
        $kata_array = explode(' ', $kalimat);

        // Kapitalisasi huruf pertama kata pertama
        $kata_array[0] = ucfirst(strtolower($kata_array[0]));

        // Loop setiap kata mulai dari kata kedua
        for ($j = 1; $j < count($kata_array); $j++) {
          $kata = strtolower($kata_array[$j]);

          // Jika kata ada dalam daftar kata kecil dan bukan di awal kalimat
          if (in_array($kata, $kata_kecil)) {
            $kata_array[$j] = $kata;
          } else {
            // Kapitalisasi kata lainnya
            $kata_array[$j] = ucfirst($kata);
          }
        }

        // Gabungkan kembali kata-kata menjadi kalimat
        $hasil .= implode(' ', $kata_array);
      }
    }

    // Tambahkan kembali tanda baca yang terpisah
    if (isset($kalimat_array[$i + 1])) {
      $hasil .= $kalimat_array[$i + 1] . ' ';
    }
  }

  return trim($hasil);
}