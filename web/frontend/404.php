<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include __DIR__ . '/parts/head.php'; ?>
        <title>404 - Page not found</title>
    </head>
    <body>
    <?php include __DIR__ . '/parts/header.php'; ?>
            <main>
                <div class="error-page">
                    <div class="error-code">404</div>
                    <div class="error-message">Page not found</div>
                    <p class="error-description">The log you're looking for doesn't exist or has expired.</p>
                    <a href="/" class="btn btn-blue">
                        <i class="fa-solid fa-home"></i>
                        Back to Home
                    </a>
                </div>
            </main>
        <?php include __DIR__ . '/parts/footer.php'; ?>
    </body>
</html>
