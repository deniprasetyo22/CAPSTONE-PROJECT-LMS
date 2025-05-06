<div class="drawer drawer-end">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-blue-400 w-full">
            <div class="container mx-auto flex items-center gap-4 px-4 py-2">
                <!-- Hamburger (mobile) -->
                <label for="my-drawer-3" class="btn btn-square btn-ghost lg:hidden">
                    <i class="fa-solid fa-bars text-white"></i>
                </label>

                <!-- Brand -->
                <?php if (in_groups('student')) : ?>
                <a href="<?= url_to('course_list') ?>" class="text-lg font-medium text-white">
                    Learning Management System
                </a>
                <?php elseif (in_groups('teacher')) : ?>
                <a href="<?= url_to('courses') ?>" class="text-lg font-medium text-white">
                    Learning Management System
                </a>
                <?php else : ?>
                <a href="/" class="text-lg font-medium text-white">
                    Learning Management System
                </a>
                <?php endif; ?>

                <div class="flex-1"></div>

                <?php if (!logged_in()) : ?>
                <div class="flex items-center space-x-2 hidden lg:flex">
                    <a href="/login" class="btn btn-sm btn-primary">Login</a>
                    <a href="/register" class="btn btn-sm btn-secondary">Register</a>
                </div>
                <?php else : ?>

                <div class="flex items-center space-x-4 hidden lg:flex">
                    <?php if (in_groups('student')) : ?>
                    <a href="<?= url_to('course_list') ?>" class="text-white font-semibold">Home</a>
                    <a href="<?= url_to('student_profile') ?>" class="text-white font-semibold">Profile</a>
                    <a href="<?= url_to('student_courses') ?>" class="text-white font-semibold">My Courses</a>
                    <?php endif; ?>
                    <a href="/logout" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white">Logout</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Drawer menu (mobile) -->
    <div class="drawer-side z-40">
        <label for="my-drawer-3" class="drawer-overlay"></label>
        <ul class="menu p-4 w-64 bg-base-100 min-h-full text-base-content">
            <li class="mb-2 text-lg font-semibold">Menu</li>

            <?php if(!logged_in()) : ?>
            <li><a href="/login" class="btn btn-sm btn-primary">Login</a></li>
            <li><a href="/register" class="btn btn-sm btn-secondary mt-2">Register</a></li>
            <?php endif; ?>

            <?php if (in_groups('student')) : ?>
            <li><a href="<?= url_to('course_list') ?>">Home</a></li>
            <li><a href="<?= url_to('student_profile') ?>">Profile</a></li>
            <li><a href="<?= url_to('student_courses') ?>">My Courses</a></li>
            <?php endif; ?>

            <?php if (logged_in()) : ?>
            <li class="mt-auto"><a href="/logout" class="btn btn-error text-white">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>