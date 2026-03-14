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
    <title>Register - Join Foundly Intelligence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    <div class="aurora-container">
        <div class="aurora-blob aurora-1"></div>
        <div class="aurora-blob aurora-2"></div>
        <div class="aurora-blob aurora-3"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="text-center mb-10" data-animate>
                    <div class="bg-gradient-to-br from-rose-500 to-purple-600 p-4 rounded-4 d-inline-block mb-4 shadow-glow" style="--glow-primary: rgba(244, 63, 94, 0.4);">
                        <i class="bi bi-person-plus-fill text-white display-5"></i>
                    </div>
                    <h1 class="display-5 text-white">New Identity.</h1>
                    <p class="text-mid text-uppercase ls-wider small fw-700">Campus Personnel Registry</p>
                </div>

                <div class="glass-panel p-5 shadow-premium" data-animate style="animation-delay: 0.1s;">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger rounded-4 mb-5 p-4 d-flex align-items-center">
                            <i class="bi bi-shield-exclamation me-3 fs-4"></i>
                            <div class="small fw-600"><?php echo htmlspecialchars($_GET['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="auth_api.php?action=register" method="POST" class="row g-6">
                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Full Legal Name</label>
                            <div class="position-relative">
                                <i class="bi bi-person position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <input type="text" name="name" class="form-input ps-10" required placeholder="John Doe">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Institutional Email</label>
                            <div class="position-relative">
                                <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <input type="email" name="email" class="form-input ps-10" required 
                                       placeholder="yourname@kiit.ac.in" 
                                       pattern=".*@kiit\.ac\.in$">
                                <div class="mt-2 extra-small text-mid opacity-50"><i class="bi bi-info-circle me-1"></i>Strictly @kiit.ac.in domain required.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Operational Role</label>
                            <div class="position-relative">
                                <i class="bi bi-briefcase position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <select name="role" class="form-input ps-10 appearance-none">
                                    <option value="user">Candidate (Student)</option>
                                    <option value="faculty">Operative (Faculty)</option>
                                    <option value="admin">Director (Admin)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-mid small fw-700 text-uppercase ls-wider mb-2 d-block">Security Key</label>
                            <div class="position-relative">
                                <i class="bi bi-lock position-absolute top-50 start-0 translate-middle-y ms-4 text-low"></i>
                                <input type="password" name="password" class="form-input ps-10" required placeholder="••••••••">
                            </div>
                        </div>

                        <div class="col-12 pt-4">
                            <button type="submit" class="btn-aurora w-100 fs-5 py-4">Establish Identity</button>
                        </div>
                    </form>

                    <div class="text-center mt-8 pt-5 border-top border-glass">
                        <p class="text-mid mb-0">Already registered? <a href="login.php" class="text-accent-primary fw-700 text-decoration-none border-bottom border-accent border-opacity-30 pb-1">Authenticate Session</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-animate]').forEach((el, i) => {
            setTimeout(() => el.classList.add('visible'), i * 100);
        });
    </script>
</body>
</html>
