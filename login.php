<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Foundly Intel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    <div class="aurora-container">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="aurora-blob aurora-3"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-10" data-animate>
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-4 rounded-4 d-inline-block mb-4 shadow-glow">
                        <i class="bi bi-shield-lock-fill text-white display-5"></i>
                    </div>
                    <h1 class="display-5 text-white">Neural login.</h1>
                    <p class="text-mid">Authorized access for @kiit.ac.in personnel only.</p>
                </div>

                <div class="glass-panel p-5 shadow-premium" data-animate style="animation-delay: 0.1s;">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger rounded-4 mb-5 p-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div class="small fw-600"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert bg-success bg-opacity-10 border-success border-opacity-20 text-success rounded-4 mb-5 p-4 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                            <div class="small fw-600"><?php echo htmlspecialchars($_GET['success']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="api/auth.php?action=login" method="POST" class="space-y-6">
                        <div>
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Institutional ID</label>
                            <div class="position-relative">
                                <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <input type="email" name="email" class="form-input ps-10" required 
                                       placeholder="identity@kiit.ac.in" 
                                       pattern=".*@kiit\.ac\.in$">
                            </div>
                        </div>

                        <div>
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Access Key</label>
                            <div class="position-relative">
                                <i class="bi bi-key position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <input type="password" name="password" class="form-input ps-10" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="btn-aurora w-100 fs-5">Initialize Session</button>
                        </div>
                    </form>

                    <div class="text-center mt-8 pt-5 border-top border-glass">
                        <p class="text-mid mb-0">No credentials? <a href="register.php" class="text-accent-primary fw-700 text-decoration-none border-bottom border-accent border-opacity-30 pb-1">Request Access</a></p>
                    </div>
                </div>
                
                <div class="text-center mt-8 opacity-40">
                    <a href="index.php" class="text-mid text-decoration-none small fw-600"><i class="bi bi-arrow-left me-2"></i>Abort and Return to Stream</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-animate]').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 150);
        });
    </script>
</body>
</html>
