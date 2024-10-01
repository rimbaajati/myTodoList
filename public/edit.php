<?php
session_start();
require 'config.php';

// Mendapatkan ID tugas dari parameter URL
$id = $_GET['id'] ?? null;

if ($id === null) {
    // Jika ID tidak valid, kembalikan ke halaman utama
    header("Location: index.php");
    exit();
}

// Ambil data tugas berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM tugas WHERE id = :id");
$stmt->execute(['id' => $id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    // Jika tugas tidak ditemukan, kembali ke halaman utama
    header("Location: index.php");
    exit();
}

// Menangani update tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $nama = htmlspecialchars($_POST['task']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $tanggal = htmlspecialchars($_POST['due_date']);
    $jam = htmlspecialchars($_POST['due_time']);
    $prioritas = htmlspecialchars($_POST['priority']);
    $kategori = htmlspecialchars($_POST['kategori']);

    $stmt = $pdo->prepare("UPDATE tugas SET nama = :nama, deskripsi = :deskripsi, tanggal = :tanggal, jam = :jam, prioritas = :prioritas, kategori = :kategori WHERE id = :id");
    $stmt->execute([
        'nama' => $nama,
        'deskripsi' => $deskripsi,
        'tanggal' => $tanggal,
        'jam' => $jam,
        'prioritas' => $prioritas,
        'kategori' => $kategori,
        'id' => $id
    ]);

    // Redirect ke halaman utama setelah pembaruan
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/style.css" rel="stylesheet">
    <link rel="icon" type="image" href="../image/logo.png">
    <title>Edit Task</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-wrap">
                <img src="../image/logo.png" alt="logo" width="40">
                <h1>Edit Task</h1>
            </div>
            <div class="navmenu">
                <a href="index.php">Back to Todo List</a>
            </div>
        </div>
    </header>

    <div class="content">
        <h1>Edit Tugas</h1>
        <form method="POST">
            <input type="text" name="task" value="<?php echo htmlspecialchars($task['nama']); ?>" required>
            <textarea name="deskripsi" required><?php echo htmlspecialchars($task['deskripsi']); ?></textarea>
            <select name="priority" required>
                <option value="Tinggi" <?php if ($task['prioritas'] == 'Tinggi') echo 'selected'; ?>>Tinggi</option>
                <option value="Sedang" <?php if ($task['prioritas'] == 'Sedang') echo 'selected'; ?>>Sedang</option>
                <option value="Rendah" <?php if ($task['prioritas'] == 'Rendah') echo 'selected'; ?>>Rendah</option>
            </select>
            <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['tanggal']); ?>" required>
            <input type="time" name="due_time" value="<?php echo htmlspecialchars($task['jam']); ?>" required>
            <input type="text" name="kategori" value="<?php echo htmlspecialchars($task['kategori']); ?>" required>
            <button type="submit" name="update_task">Perbarui Tugas</button>
        </form>
    </div>
</body>
</html>
