<?php
session_start();
require_once 'config/convex.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$convex = new ConvexDB();
$userId = $_SESSION['user_id'];
$items = $convex->getItems();
$myItems = [];
$error = null;

if (isset($items['error'])) {
    $error = $items['error'];
} else {
    $myItems = array_filter($items, function($item) use ($userId) {
        return isset($item['user_id']) && $item['user_id'] === $userId;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Center - Foundly Intel</title>
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
        <header class="mb-10 text-center text-lg-start d-lg-flex justify-content-between align-items-end" data-animate>
            <div>
                <span class="status-pill found mb-3 d-inline-block">Operational Protocol</span>
                <h1 class="display-3 text-white mb-3">Asset <span class="text-gradient">Management</span></h1>
                <p class="text-mid fs-5 mb-0" style="max-width: 600px;">Track the lifecycle of your reported items and respond to verification requests.</p>
            </div>
            <div class="mt-5 mt-lg-0">
                <a href="report_item.php" class="btn-aurora fs-5 px-8"><i class="bi bi-plus-lg me-2"></i>New Broadcast</a>
            </div>
        </header>

        <div class="glass-panel p-0 overflow-hidden shadow-premium" data-animate style="animation-delay: 0.1s;">
            <div class="p-5 border-bottom border-glass d-flex justify-content-between align-items-center bg-white bg-opacity-5">
                <h3 class="mb-0 text-white fs-4">Registry Feed</h3>
                <div class="status-pill found bg-opacity-20"><?php echo count($myItems); ?> Records Active</div>
            </div>

            <?php if ($error): ?>
                <div class="p-10 text-center">
                    <i class="bi bi-cloud-slash-fill display-3 text-accent-tertiary mb-4"></i>
                    <h2 class="text-white">Database Link Severed</h2>
                    <p class="text-mid"><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php elseif (empty($myItems)): ?>
                <div class="p-10 text-center py-15">
                    <div class="bg-indigo-500 bg-opacity-10 p-5 rounded-circle d-inline-block mb-4">
                        <i class="bi bi-stack-overflow display-1 text-accent-primary opacity-50"></i>
                    </div>
                    <h3 class="text-white mb-3">Zero Records Discovered</h3>
                    <p class="text-mid mb-5 mx-auto" style="max-width: 450px;">You haven't initiated any asset broadcasts yet. Your contribution helps the campus stay connected.</p>
                    <a href="report_item.php" class="btn-aurora">Launch First Protocol</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-low small fw-800 text-uppercase ls-wider">
                                <th class="px-5 py-4 border-glass">Asset Identification</th>
                                <th class="px-5 py-4 border-glass text-center">Status Matrix</th>
                                <th class="px-5 py-4 border-glass text-end">Operational Controls</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <?php foreach ($myItems as $item): ?>
                            <tr class="align-middle transition-all hover:bg-white hover:bg-opacity-5">
                                <td class="px-5 py-5 border-glass border-opacity-5">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-4 overflow-hidden me-4 shadow-sm" style="width: 80px; height: 80px;">
                                            <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" class="w-100 h-100 object-fit-cover" alt="Thumb">
                                        </div>
                                        <div>
                                            <div class="fw-800 text-white fs-5 mb-1"><?php echo htmlspecialchars($item['item_name']); ?></div>
                                            <div class="small text-mid text-truncate" style="max-width: 300px;">
                                                <i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($item['location']); ?> • <?php echo htmlspecialchars($item['category'] ?? 'General'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-glass border-opacity-5 text-center">
                                    <span class="status-pill <?php echo $item['status']; ?>">
                                        <i class="bi bi-record-circle-fill me-2 fs-6"></i><?php echo $item['status']; ?>
                                    </span>
                                    <div class="mt-2 extra-small text-low fw-700 text-uppercase ls-wider">
                                        <?php echo date('M d, H:i', ($item['created_at'] ?? time()*1000) / 1000); ?>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-glass border-opacity-5 text-end">
                                    <a href="edit_item.php?id=<?php echo $item['_id']; ?>" class="btn-ghost px-4 py-2 small fw-700">
                                        <i class="bi bi-pencil-square me-2 text-accent-primary"></i>Modify Report
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-animate]').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 150);
        });
    </script>
</body>
</html>
