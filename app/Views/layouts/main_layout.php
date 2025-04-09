<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Title Section -->
        <title><?= $this->renderSection('title') ?></title>

        <!-- Daisy UI -->
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <!-- Daisy UI Themes -->
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />

        <!-- FontAwesome -->
        <script src="https://kit.fontawesome.com/c4fc535117.js" crossorigin="anonymous"></script>

        <!-- Sweetalert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- ChartJS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </head>

    <body class="flex flex-col min-h-screen">
        <!-- Header Section -->
        <?php if (empty($hideHeader)): ?>
        <header>
            <?= $this->include('partials/header') ?>
        </header>
        <?php endif; ?>

        <!-- Content Section -->
        <div class="flex-grow">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer Section -->
        <footer>
            <?= $this->include('partials/footer') ?>
        </footer>

        <!-- Pristine JS -->
        <script src="<?= base_url('js/pristine.js') ?>"></script>
    </body>

</html>