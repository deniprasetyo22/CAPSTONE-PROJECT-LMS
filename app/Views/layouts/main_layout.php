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

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Quicksand:wght@300..700&family=Roboto+Flex:opsz,wght@8..144,100..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet">

        <style>
        body {
            font-family: "Quicksand", sans-serif;
        }
        </style>


    </head>

    <body class="flex flex-col min-h-screen">
        <!-- Header Section -->
        <?php if (!in_groups(['administrator','teacher'])) : ?>
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