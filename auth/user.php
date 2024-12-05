<?php
session_start();
require "../connection.php";
require "../utility.php";

$sesi = jwtD($_SESSION["logindata"], $config["key"]);

if(!($sesi->role == "admin")) {
  redirect("..");
}

// Tambah pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $is_admin = $_POST['is_admin'];

    $query = "INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$username, $password, $is_admin]);
}

// Hapus pengguna
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
}

// Ambil data pengguna
$query = "SELECT * FROM users";
$stmt = $conn->query($query);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .container { width: 80%; margin: 0 auto; }
        button { padding: 8px 16px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        form input, form select { padding: 8px; width: 100%; margin: 4px 0; }
        .title { display: flex; align-items: center; gap: 10px }
    </style>
</head>
<body>

<div class="container">
  <div class="title">
    <a href=".."><img width="20" src="https://www.svgrepo.com/show/522506/close.svg" alt=""></a>
    <h1>Manajemen Pengguna</h1>
  </div>
    
    <!-- Form untuk menambah pengguna -->
    <h3>Tambah Pengguna</h3>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <label for="is_admin">Is Admin:</label>
        <select name="is_admin" id="is_admin">
            <option value="0">Tidak</option>
            <option value="1">Ya</option>
        </select>
        
        <button type="submit">Tambah Pengguna</button>
    </form>

    <!-- Tabel data pengguna -->
    <h3>Daftar Pengguna</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Admin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['is_admin'] ? 'Ya' : 'Tidak'; ?></td>
                    <td>
                        <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Hapus pengguna?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
