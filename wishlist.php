<?php
// wishlist.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'category' => $_POST['category'] ?? '',
        'status' => $_POST['status'] ?? 'WANT',
        'priority' => $_POST['priority'] ?? 'MEDIUM',
        'target_date' => !empty($_POST['target_date']) ? $_POST['target_date'] : null
    ];
    if ($action === 'create') {
        createRecord('wishlist', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('wishlist', $_POST['id'], $data);
    }
    redirect('/eternity/wishlist.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('wishlist', $_GET['delete']);
    redirect('/eternity/wishlist.php');
}

$items = getAll('wishlist');
usort($items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Wishlist'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>⭐ Wishlist</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Wishlist</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category">
                        <option value="Things">Things</option>
                        <option value="Food">Food</option>
                        <option value="Date">Date</option>
                        <option value="Travel">Travel</option>
                        <option value="Experience">Experience</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="WANT">Want</option>
                        <option value="PLANNED">Planned</option>
                        <option value="DONE">Done</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prioritas</label>
                    <select name="priority">
                        <option value="LOW">Low</option>
                        <option value="MEDIUM">Medium</option>
                        <option value="HIGH">High</option>
                    </select>
                </div>
                <div class="form-group"><label>Target Date</label><input type="date" name="target_date"></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">⭐</div><p>Belum ada wishlist.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <h3><?php echo escape($item['title']); ?></h3>
                            <span class="badge <?php echo $item['status'] === 'DONE' ? 'badge-green' : 'badge-primary'; ?>">
                                <?php echo escape($item['status'] ?? 'WANT'); ?>
                            </span>
                        </div>
                        <p><?php echo escape($item['description'] ?? ''); ?></p>
                        <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                            <?php if (!empty($item['category'])): ?>
                                <span class="badge badge-blue"><?php echo escape($item['category']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['priority'])): ?>
                                <span class="badge"><?php echo escape($item['priority']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($item['target_date'])): ?>
                                <span class="badge">📅 <?php echo formatDate($item['target_date']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Wishlist</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                    <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . escape($item['description'] ?? '') . '</textarea></div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="category">
                                            <option value="Things" ' . ($item['category'] === 'Things' ? 'selected' : '') . '>Things</option>
                                            <option value="Food" ' . ($item['category'] === 'Food' ? 'selected' : '') . '>Food</option>
                                            <option value="Date" ' . ($item['category'] === 'Date' ? 'selected' : '') . '>Date</option>
                                            <option value="Travel" ' . ($item['category'] === 'Travel' ? 'selected' : '') . '>Travel</option>
                                            <option value="Experience" ' . ($item['category'] === 'Experience' ? 'selected' : '') . '>Experience</option>
                                            <option value="Other" ' . ($item['category'] === 'Other' ? 'selected' : '') . '>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status">
                                            <option value="WANT" ' . ($item['status'] === 'WANT' ? 'selected' : '') . '>Want</option>
                                            <option value="PLANNED" ' . ($item['status'] === 'PLANNED' ? 'selected' : '') . '>Planned</option>
                                            <option value="DONE" ' . ($item['status'] === 'DONE' ? 'selected' : '') . '>Done</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Prioritas</label>
                                        <select name="priority">
                                            <option value="LOW" ' . ($item['priority'] === 'LOW' ? 'selected' : '') . '>Low</option>
                                            <option value="MEDIUM" ' . ($item['priority'] === 'MEDIUM' ? 'selected' : '') . '>Medium</option>
                                            <option value="HIGH" ' . ($item['priority'] === 'HIGH' ? 'selected' : '') . '>High</option>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Target Date</label><input type="date" name="target_date" value="' . ($item['target_date'] ?? '') . '"></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/wishlist.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>