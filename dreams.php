<?php
// dreams.php
require_once 'includes/auth.php';
requireAuth();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'status' => $_POST['status'] ?? 'DREAM',
        'target_date' => $_POST['target_date'] ?? null
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        updateRecord('dreams', $_POST['id'], $data);
    } else {
        createRecord('dreams', $data);
    }
    redirect('dreams.php');
}

if (isset($_GET['delete'])) {
    deleteRecord('dreams', $_GET['delete']);
    redirect('dreams.php');
}

$dreams = getAll('dreams');
?>
<?php $page_title = 'Dreams'; include 'includes/header.php'; include 'includes/navbar.php'; ?>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h1>🌟 Dreams</h1>
        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
            <h2>Tambah Dream</h2>
            <form method="POST" action="dreams.php">
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
        '); ?>`)">Tambah Dream</button>
    </div>
    <div style="margin-top:20px;">
        <?php if (empty($dreams)): ?>
            <div class="empty-state">
                <div class="emoji">🌙</div>
                <p>Belum ada impian. Mulai mimpi besar bersama.</p>
            </div>
        <?php else: ?>
            <?php foreach ($dreams as $dream): ?>
                <div class="card" style="margin-bottom:15px;">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <h3><?php echo htmlspecialchars($dream['title']); ?></h3>
                        <span class="badge badge-<?php echo strtolower($dream['status'] ?? 'DREAM'); ?>">
                            <?php echo htmlspecialchars($dream['status'] ?? 'DREAM'); ?>
                        </span>
                    </div>
                    <p><?php echo htmlspecialchars($dream['description'] ?? ''); ?></p>
                    <?php if (!empty($dream['target_date'])): ?>
                        <span class="badge">Target: <?php echo formatDate($dream['target_date']); ?></span>
                    <?php endif; ?>
                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn btn-secondary" onclick="openModal(`<?php echo htmlspecialchars('
                            <h2>Edit Dream</h2>
                            <form method="POST" action="dreams.php">
                                <input type="hidden" name="id" value="' . $dream['id'] . '">
                                <div class="form-group"><label>Judul</label><input type="text" name="title" value="' . htmlspecialchars($dream['title']) . '" required></div>
                                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="3">' . htmlspecialchars($dream['description'] ?? '') . '</textarea></div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="DREAM" ' . ($dream['status'] == 'DREAM' ? 'selected' : '') . '>Dream</option>
                                        <option value="PLANNED" ' . ($dream['status'] == 'PLANNED' ? 'selected' : '') . '>Planned</option>
                                        <option value="ACHIEVED" ' . ($dream['status'] == 'ACHIEVED' ? 'selected' : '') . '>Achieved</option>
                                    </select>
                                </div>
                                <div class="form-group"><label>Target Date</label><input type="date" name="target_date" value="' . ($dream['target_date'] ?? '') . '"></div>
                                <button type="submit" class="btn">Update</button>
                            </form>
                        '); ?>`)">Edit</button>
                        <a href="dreams.php?delete=<?php echo $dream['id']; ?>" class="btn" style="background:#000;color:#fff;" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
