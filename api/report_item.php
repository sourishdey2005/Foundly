session_start();
require_once '../config/convex.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broadcast Asset - Foundly Intel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
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
                    <span class="status-pill lost mb-4 d-inline-block">Intelligence Submission</span>
                    <h1 class="display-3 text-white">Report Lost <span class="text-gradient">Asset</span></h1>
                    <p class="text-mid fs-5 mx-auto" style="max-width: 650px;">Initiate a campus-wide broadcast to recover your missing item. Accuracy speeds up identification.</p>
                </header>

                <div class="glass-panel p-5 shadow-premium" data-animate style="animation-delay: 0.1s;">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger rounded-4 mb-8 p-4 d-flex align-items-center">
                            <i class="bi bi-shield-x me-3 fs-3"></i>
                            <div class="fw-600"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="item_create_api.php" method="POST" enctype="multipart/form-data" class="row g-8">
                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Asset Nomenclature</label>
                            <input type="text" name="item_name" class="form-input" required placeholder="e.g. Vintage Leather Wallet">
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Category Classification</label>
                            <select name="category" class="form-input appearance-none" required>
                                <option value="Electronics">Electronics</option>
                                <option value="Documents">Documents/IDs</option>
                                <option value="Wallets">Wallets/Bags</option>
                                <option value="Keys">Keys</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Miscellaneous" selected>Miscellaneous</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Incident Zone</label>
                            <input type="text" name="location" class="form-input" required placeholder="e.g. Central Library, 3rd Floor">
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Last Known Timestamp</label>
                            <input type="date" name="date_lost" class="form-input" required>
                        </div>

                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Descriptive Logistics</label>
                            <textarea name="description" class="form-input" rows="4" required placeholder="Physical characteristics, serial numbers, or unique identifiers..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Visual Evidence</label>
                            <div class="p-8 rounded-5 glass-panel text-center border-dashed border-accent cursor-pointer" onclick="document.querySelector('#image-upload').click();">
                                <i class="bi bi-camera-fill display-3 text-accent-primary mb-4 d-block"></i>
                                <input type="file" name="item_image" id="image-upload" class="d-none" accept=".jpg,.jpeg,.png" required onchange="this.nextElementSibling.innerText = this.files[0].name">
                                <h4 class="text-white mb-2">Upload Asset Visuals</h4>
                                <p class="text-low mb-0 small" id="file-name">Optimal format: JPG/PNG, Max 5MB</p>
                            </div>
                        </div>

                        <div class="col-12 pt-6">
                            <div class="d-flex gap-4">
                                <button type="submit" class="btn-aurora flex-grow-1 fs-5 py-4">Authenticate Broadcast</button>
                                <a href="index.php" class="btn-ghost d-flex align-items-center px-6">Discard</a>
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
