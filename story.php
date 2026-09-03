<?php
// story.php
require_once 'includes/auth.php';
requireAuth();
$page_title = 'Our Story';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container">
    <h1 style="font-family:var(--font-display);">📖 Our Story</h1>
    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:20px;">
        <a href="/eternity/timeline.php" class="btn btn-blue" style="flex:1; text-align:center;">📜 Timeline</a>
        <a href="/eternity/memories.php" class="btn" style="flex:1; text-align:center;">📸 Memories</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>