<?php
session_start();
require 'config.php';  

// Handle task deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Pastikan id berupa integer
    $stmt = $pdo->prepare("DELETE FROM tugas WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $nama = htmlspecialchars($_POST['task']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $tanggal = htmlspecialchars($_POST['due_date']);
    $jam = htmlspecialchars($_POST['due_time']);
    $prioritas = htmlspecialchars($_POST['priority']);
    $kategori = htmlspecialchars($_POST['kategori']);

    if (!empty($nama)) {
        $stmt = $pdo->prepare("INSERT INTO tugas (nama, deskripsi, tanggal, jam, prioritas, kategori) VALUES (:nama, :deskripsi, :tanggal, :jam, :prioritas, :kategori)");
        $stmt->execute([
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'tanggal' => $tanggal,
            'jam' => $jam,
            'prioritas' => $prioritas,
            'kategori' => $kategori
        ]);
    }
}

// Handle task update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $id = intval($_POST['id']);
    $nama = htmlspecialchars($_POST['task']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $tanggal = htmlspecialchars($_POST['due_date']);
    $jam = htmlspecialchars($_POST['due_time']);
    $prioritas = htmlspecialchars($_POST['priority']);
    $kategori = htmlspecialchars($_POST['kategori']);

    if (!empty($id)) {
        $stmt = $pdo->prepare("UPDATE tugas SET nama = :nama, deskripsi = :deskripsi, tanggal = :tanggal, jam = :jam, prioritas = :prioritas, kategori = :kategori WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'tanggal' => $tanggal,
            'jam' => $jam,
            'prioritas' => $prioritas,
            'kategori' => $kategori
        ]);
    }
}

// Fetch all tasks from database
$tasks = $pdo->query("SELECT * FROM tugas ORDER BY tanggal, jam")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/style.css" rel="stylesheet">
    <link rel="icon" type="image" href="../image/logo.png">
    <title>My TodoList</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-wrap">
                <img src="../image/logo.png" alt="logo" width="40">
                <h1>My TodoList</h1>
            </div>
            <nav class="navmenu">
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                    <li><a href="#beranda">
                        <img src="../image/profile.png" alt="profile" width="40">
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="sidebar">
        <h1>My TodoList</h1>
        <ul>
            <li><a href="#beranda">🏠 Beranda</a></li>
            <li><a href="#task">📇 Tugas</a></li>
            <li><a href="#pengaturan">⚙️ Pengaturan</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Manajemen Tugas</h1>

        <div>
            <form method="POST">
                <input type="text" name="task" id="inputTugas" placeholder="Tambahkan Tugas" required>
                <textarea name="deskripsi" placeholder="Deskripsi Tugas"></textarea>
                <select name="priority" required>
                    <option value="">Pilih Prioritas</option>
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Rendah">Rendah</option>
                </select>
                <input type="date" name="due_date" required>
                <input type="time" name="due_time" required>
                <input type="text" name="kategori" placeholder="Kategori Tugas" required>

                <button type="submit" name="add_task">➕</button>
            </form>
        </div>

        <div class="daftar-tugas">
            <h2>Tugasku</h2>
            <ul id="daftarTugas">
                <?php foreach ($tasks as $task): ?>
                    <li>
                        <?php echo htmlspecialchars($task['nama']) . " (Prioritas: " . htmlspecialchars($task['prioritas']) . ", Tanggal: " . htmlspecialchars($task['tanggal']) . ", Jam: " . htmlspecialchars($task['jam']) . ", Kategori: " . htmlspecialchars($task['kategori']) . ")"; ?>
                        <a href="edit.php?id=<?php echo $task['id']; ?>">✏️ Edit</a>
                        <a href="?action=delete&id=<?php echo $task['id']; ?>">❌ Hapus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
