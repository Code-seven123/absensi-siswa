<?php
    require_once __DIR__ . "/connection.php";
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
