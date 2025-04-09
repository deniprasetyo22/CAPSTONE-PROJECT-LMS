<div class="h-full flex flex-col bg-gray-800 text-white">
    <!-- Logo -->
    <div class="p-4 text-center text-lg font-bold bg-gray-900">
        Admin Panel
    </div>

    <!-- Menu Items -->
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <?php
            $currentUrl = current_url();
            ?>

            <?php if(logged_in()) : ?>
            <li>
                <a href="<?= url_to('home') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('home') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Home</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('admin-dashboard') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('admin-dashboard') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <?php endif ?>

            <?php if(in_groups('product_manager')) : ?>
            <li>
                <a href="<?= url_to('manager-dashboard') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('manager-dashboard') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <?php endif ?>

            <?php if(in_groups('administrator') || (in_groups('product_manager'))) : ?>
            <li>
                <a href="<?= url_to('report') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('report') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-3">Report</span>
                </a>
            </li>
            <?php endif ?>

            <?php if(in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('auth-user') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('auth-user') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Users</span>
                </a>
            </li>
            <?php endif ?>

            <?php if(in_groups('administrator') || (in_groups('product_manager'))) : ?>
            <li>
                <a href="<?= url_to('product') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('product') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-box w-5"></i>
                    <span class="ml-3">Products</span>
                </a>
            </li>
            <?php endif ?>

            <?php if(in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('role') ?>"
                    class="flex items-center p-2 rounded <?= $currentUrl == url_to('role') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Roles</span>
                </a>
            </li>
            <?php endif ?>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-700">
        <?php if(logged_in()) : ?>
        <a href="/logout"
            class="w-full flex items-center justify-center p-2 rounded bg-red-600 hover:bg-red-700 text-white">
            <i class="fa-solid fa-sign-out-alt w-5"></i>
            <span class="ml-3">Logout</span>
        </a>
        <?php endif; ?>
    </div>
</div>