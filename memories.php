<?php
// memories.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);
    if (!in_array($fileType, $allowed)) die('Format gambar tidak didukung.');
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) die('Ukuran gambar maksimal 5MB.');
    
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $fileData = file_get_contents($_FILES['image']['tmp_name']);
    $uploadResult = uploadFile('memories', $filename, $fileData, $fileType);
    
    if ($uploadResult['status'] === 200) {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'memory_date' => $_POST['memory_date'] ?? date('Y-m-d'),
            'location' => $_POST['location'] ?? '',
            'image_path' => 'memories/' . $filename
        ];
        createRecord('memories', $data);
    }
    redirect('/eternity/memories.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('memories', $_GET['delete']);
    redirect('/eternity/memories.php');
}

$memories = getAll('memories');
usort($memories, function($a, $b) {
    return strtotime($b['memory_date']) - strtotime($a['memory_date']);
});
?>
<?php $page_title = 'Memories'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>📸 Memories</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Memory</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group"><label>Tanggal</label><input type="date" name="memory_date" required></div>
                <div class="form-group"><label>Lokasi</label><input type="text" name="location"></div>
                <div class="form-group"><label>Gambar</label><input type="file" name="image" accept="image/*" required></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div class="memory-grid" style="margin-top:20px;">
        <?php if (empty($memories)): ?>
            <div class="empty-state" style="grid-column:1/-1;"><div class="emoji">📸</div><p>Belum ada kenangan.</p></div>
        <?php else: ?>
            <?php foreach ($memories as $memory): ?>
                <div class="card memory-card">
                    <?php if (!empty($memory['image_path'])): ?>
                        <img src="<?php echo generateStorageUrl($memory['image_path']); ?>" alt="<?php echo escape($memory['title']); ?>">
                    <?php else: ?>
                        <div style="height:200px; background:#eee; display:flex; align-items:center; justify-content:center; border:var(--border-thick); border-radius:var(--radius);">No Image</div>
                    <?php endif; ?>
                    <h3><?php echo escape($memory['title']); ?></h3>
                    <p><?php echo escape($memory['description'] ?? ''); ?></p>
                    <div class="badge"><?php echo formatDate($memory['memory_date']); ?></div>
                    <?php if (!empty($memory['location'])): ?>
                        <span class="badge badge-blue">📍 <?php echo escape($memory['location']); ?></span>
                    <?php endif; ?>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <a href="/eternity/memories.php?delete=<?php echo $memory['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>