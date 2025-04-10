<div class="drawer drawer-end">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-blue-400 w-full">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Left: Brand & Hamburger (mobile) -->
                <div class="flex items-center">
                    <?php if(logged_in()) : ?>
                    <label for="my-drawer-3" class="btn btn-square btn-ghost lg:hidden">
                        <i class="fa-solid fa-bars text-white"></i>
                    </label>
                    <?php endif ?>
                    <a href="/" class="text-lg font-medium text-white ml-2">
                        Learning Management System
                    </a>
                </div>

                <!-- Right: Menu (desktop) -->
                <div class="hidden lg:flex">
                    <ul class="menu menu-horizontal items-center">
                        <?php if(logged_in()) : ?>
                        <li>
                            <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-md">
                                Logout
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer menu (mobile) -->
    <div class="drawer-side z-40">
        <label for="my-drawer-3" class="drawer-overlay"></label>
        <ul class="menu p-4 w-64 bg-base-100 min-h-full text-base-content">
            <li class="mb-2 text-lg font-semibold">Menu</li>
            <?php if(logged_in()) : ?>
            <li>
                <a href="/logout" class="btn btn-error text-white">Logout</a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>