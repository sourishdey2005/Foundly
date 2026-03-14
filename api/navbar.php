<nav class="navbar-floating px-4">
    <div class="d-flex align-items-center">
        <a class="navbar-brand d-flex align-items-center me-4" href="index.php">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-2 rounded-3 me-3 shadow-glow">
                <i class="bi bi-search-heart-fill text-white fs-4"></i>
            </div>
            <span class="font-heading fs-4 text-white text-gradient">Foundly</span>
        </a>
        
        <div class="d-none d-lg-flex gap-4">
            <a class="nav-link fw-600 text-mid transition-all hover:text-white" href="index.php">Explore</a>
            <a class="nav-link fw-600 text-mid transition-all hover:text-white" href="report_item.php">Report Lost</a>
            <a class="nav-link fw-600 text-mid transition-all hover:text-white" href="my_reports.php">My Activity</a>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-block text-end">
                    <div class="small text-mid fw-500">Welcome back</div>
                    <div class="fw-700 text-white"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                </div>
                <div class="vr bg-white opacity-10 height-100 mx-2"></div>
                <a href="auth_api.php?action=logout" class="btn-ghost d-flex align-items-center justify-content-center p-2 rounded-circle" style="width: 44px; height: 44px;">
                    <i class="bi bi-power fs-5 text-accent-tertiary"></i>
                </a>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-ghost px-4 py-2">Login</a>
            <a href="register.php" class="btn-aurora px-4 py-2">Get Started</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Spacer for floating navbar -->
<div style="height: 120px;"></div>
