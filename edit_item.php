<?php
session_start();
require_once 'config/convex.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$itemId = $_GET['id'] ?? null;
if (!$itemId) {
    header("Location: my_reports.php");
    exit();
}

$convex = new ConvexDB();
$items = $convex->getItems();
$item = null;
foreach ($items as $i) {
    if (is_array($i) && isset($i['_id']) && $i['_id'] === $itemId && $i['user_id'] === $_SESSION['user_id']) {
        $item = $i;
        break;
    }
}

if (!$item) {
    header("Location: my_reports.php?error=Item not found or unauthorized");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset - Foundly Intel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="aurora-container">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="aurora-blob aurora-3"></div>
    </div>

    <?php include 'navbar.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-10" data-animate>
                    <span class="status-pill found mb-4 d-inline-block">Asset Modification</span>
                    <h1 class="display-3 text-white">Refine <span class="text-gradient">Broadcast</span></h1>
                    <p class="text-mid fs-5 mx-auto" style="max-width: 650px;">Update the logistics of your reported asset to maintain data integrity across the campus registry.</p>
                </header>

                <div class="glass-panel p-5 shadow-premium" data-animate style="animation-delay: 0.1s;">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger rounded-4 mb-8 p-4 d-flex align-items-center">
                            <i class="bi bi-shield-x me-3 fs-3"></i>
                            <div class="fw-600"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="api/edit_item.php" method="POST" enctype="multipart/form-data" class="row g-8">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['_id']); ?>">
                        
                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Asset Nomenclature</label>
                            <input type="text" name="item_name" class="form-input" required value="<?php echo htmlspecialchars($item['item_name']); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Category Classification</label>
                            <select name="category" class="form-input appearance-none" required>
                                <?php 
                                $categories = ["Electronics", "Documents", "Wallets", "Keys", "Clothing", "Miscellaneous"];
                                foreach ($categories as $cat) {
                                    $selected = (($item['category'] ?? 'Miscellaneous') === $cat) ? 'selected' : '';
                                    echo "<option value=\"$cat\" $selected>$cat</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Incident Zone</label>
                            <input type="text" name="location" class="form-input" required value="<?php echo htmlspecialchars($item['location']); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Last Known Timestamp</label>
                            <input type="date" name="date_lost" class="form-input" required value="<?php echo htmlspecialchars($item['date_lost']); ?>">
                        </div>

                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Descriptive Logistics</label>
                            <textarea name="description" class="form-input" rows="4" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Visual Synchronisation (Optional)</label>
                            <div class="p-8 rounded-5 glass-panel text-center border-dashed border-accent cursor-pointer" onclick="document.querySelector('#image-upload').click();">
                                <i class="bi bi-images display-3 text-accent-secondary mb-4 d-block"></i>
                                <input type="file" name="item_image" id="image-upload" class="d-none" accept=".jpg,.jpeg,.png" onchange="this.nextElementSibling.innerText = this.files[0].name">
                                <h4 class="text-white mb-2">Update Asset Visuals</h4>
                                <p class="text-low mb-0 small" id="file-name">Optimal format: JPG/PNG • Current: <?php echo htmlspecialchars($item['image_path']); ?></p>
                            </div>
                        </div>

                        <div class="col-12 pt-6">
                            <div class="d-flex gap-4">
                                <button type="submit" class="btn-aurora flex-grow-1 fs-5 py-4">Apply Neural Update</button>
                                <a href="my_reports.php" class="btn-ghost d-flex align-items-center px-6">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-animate]').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 100);
        });
    </script>
</body>
</html>
