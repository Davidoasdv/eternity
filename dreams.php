<?php
// dreams.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'status' => $_POST['status'] ?? 'DREAM',
        'target_date' => !empty($_POST['target_date']) ? $_POST['target_date'] : null
    ];
    if ($action === 'create') {
        createRecord('dreams', $data);
    } elseif ($action === 'update' && isset($_POST['id'])) {
        updateRecord('dreams', $_POST['id'], $data);
    }
    redirect('/eternity/dreams.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('dreams', $_GET['delete']);
    redirect('/eternity/dreams.php');
}

$items = getAll('dreams');
usort($items, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
?>
<?php $page_title = 'Dreams'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>🌙 Dreams</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo escape('
            <h2>Tambah Dream</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group"><label>Judul</label><input type="text" name="title" required></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3"></textarea></div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="DREAM">Dream</option>
                        <option value="PLANNED">Planned</option>
                        <option value="ACHIEVED">Achieved</option>
                    </select>
                </div>
                <div class="form-group"><label>Target Date</label><input type="date" name="target_date"></div>
                <button type="submit" class="btn">Simpan</button>
            </form>
        '); ?>`)">Tambah</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($items)): ?>
            <div class="empty-state"><div class="emoji">🌙</div><p>Belum ada mimpi.</p></div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:20px;">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <h3>🌙 <?php echo escape($item['title']); ?></h3>
                            <span class="badge <?php echo $item['status'] === 'ACHIEVED' ? 'badge-green' : 'badge-primary'; ?>">
                                <?php echo escape($item['status'] ?? 'DREAM'); ?>
                            </span>
                        </div>
                        <p><?php echo escape($item['description'] ?? ''); ?></p>
                        <?php if (!empty($item['target_date'])): ?>
                            <div style="margin-top:10px;"><span class="badge">📅 Target: <?php echo formatDate($item['target_date']); ?></span></div>
                        <?php endif; ?>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button class="btn btn-secondary" style="padding:6px 12px;font-size:0.8rem;" onclick="openModal(`<?php echo escape('
                                <h2>Edit Dream</h2>
                                <form method="POST">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="' . $item['id'] . '">
                                    <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . escape($item['title']) . '" required></div>
                                    <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . escape($item['description'] ?? '') . '</textarea></div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status">
                                            <option value="DREAM" ' . ($item['status'] === 'DREAM' ? 'selected' : '') . '>Dream</option>
                                            <option value="PLANNED" ' . ($item['status'] === 'PLANNED' ? 'selected' : '') . '>Planned</option>
                                            <option value="ACHIEVED" ' . ($item['status'] === 'ACHIEVED' ? 'selected' : '') . '>Achieved</option>
                                        </select>
                                    </div>
                                    <div class="form-group"><label>Target Date</label><input type="date" name="target_date" value="' . ($item['target_date'] ?? '') . '"></div>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            '); ?>`)">Edit</button>
                            <a href="/eternity/dreams.php?delete=<?php echo $item['id']; ?>" class="btn" style="background:#000;color:#fff;padding:6px 12px;font-size:0.8rem;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>