<?php
session_start();

// Inisialisasi daftar tugas dalam sesi
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Menangani penghapusan tugas
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    unset($_SESSION['tasks'][$_GET['id']]);
}

// Menangani penambahan tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $task = htmlspecialchars($_POST['task']);
    $priority = htmlspecialchars($_POST['priority']);
    if (!empty($task)) {
        $_SESSION['tasks'][] = ['task' => $task, 'priority' => $priority];
    }
}

// Menangani pembaruan tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $id = $_POST['id'];
    $task = htmlspecialchars($_POST['task']);
    $priority = htmlspecialchars($_POST['priority']);
    $_SESSION['tasks'][$id] = ['task' => $task, 'priority' => $priority];
}

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
            <div class="navmenu">
                <a href="#beranda">Beranda</a>
                <a href="#kontak">Kontak</a>
                <a href="#kontak">
                    <img src="../image/profile.png" alt="profile" width="40">
                </a>
            </div>
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
                <select name="priority" required>
                    <option value="">Pilih Prioritas</option>
                    <option value="High">Tinggi</option>
                    <option value="Medium">Sedang</option>
                    <option value="Low">Rendah</option>
                </select>
                <button type="submit" name="add_task">➕</button>
            </form>
        </div>

        <div class="daftar-tugas">
            <h2>Tugasku</h2>
            <ul id="daftarTugas">
                <?php foreach ($_SESSION['tasks'] as $id => $task): ?>
                    <li>
                        <?php echo htmlspecialchars($task['task']) . " (Prioritas: " . htmlspecialchars($task['priority']) . ")"; ?>
                        <a href="edit_task.php?id=<?php echo $id; ?>">✏️ Edit</a>
                        <a href="?action=delete&id=<?php echo $id; ?>">❌ Hapus</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
