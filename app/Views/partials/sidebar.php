<aside id="sidebar"
    class="w-64 h-full bg-gray-800 text-white p-6 transform transition-transform duration-200 ease-in-out flex flex-col justify-between">
    <div>

        <?php if (in_groups('administrator')) : ?>
        <h2 class="text-center text-xl font-bold mb-4">Admin Panel</h2>
        <?php elseif (in_groups('teacher')) : ?>
        <h2 class="text-center text-xl font-bold mb-4">Teacher Panel</h2>
        <?php endif ?>
        <ul class="menu w-full">
            <?php $currentUrl = current_url(); ?>

            <?php if (in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('admin_dashboard') ?>"
                    class="<?= $currentUrl == url_to('admin_dashboard') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
            </li>
            <?php elseif (in_groups('teacher')) : ?>
            <li>
                <a href="<?= url_to('teacher_dashboard') ?>"
                    class="<?= $currentUrl == url_to('teacher_dashboard') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-book mr-2"></i> Dashboard
                </a>
            </li>
            <?php endif ?>

            <?php if (in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('reports') ?>"
                    class="<?= $currentUrl == url_to('reports') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-chart-bar mr-2"></i> Reports
                </a>
            </li>
            <?php endif ?>

            <?php if (in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('users') ?>"
                    class="<?= $currentUrl == url_to('users') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
            </li>
            <?php elseif (in_groups('teacher')) : ?>
            <li>
                <a href="<?= url_to('teacher_courses') ?>"
                    class="<?= $currentUrl == url_to('teacher_courses') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fa-solid fa-book mr-2"></i> Courses
                </a>
            </li>
            <?php endif ?>

            <?php if (in_groups('administrator')) : ?>
            <li>
                <a href="<?= url_to('levels') ?>"
                    class="<?= $currentUrl == url_to('levels') ? 'bg-gray-700' : '' ?> hover:bg-gray-700">
                    <i class="fas fa-layer-group mr-2"></i> Levels
                </a>
            </li>
            <?php endif ?>
        </ul>
    </div>

    <div class="mt-4">
        <a href="<?= url_to('logout') ?>" class="btn btn-error w-full">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</aside>