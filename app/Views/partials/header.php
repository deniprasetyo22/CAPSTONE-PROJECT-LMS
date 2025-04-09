<div class="drawer">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="navbar bg-blue-400 w-full">
            <div class="container mx-auto">
                <div class="flex lg:hidden items-center">
                    <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-6 w-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    <a href="#" class="text-lg font-medium text-white">
                        Learning Management System
                    </a>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex-1 px-2 text-xl font-medium text-white">
                        <a href="#">
                            <span class="hidden lg:block">Learning Management System</span>
                        </a>
                    </div>
                    <div class="hidden flex-none lg:block">
                        <ul class="menu menu-horizontal">
                            <!-- Navbar menu content here -->
                            <li>
                                <ul class="flex items-center">
                                    <?php if(logged_in()) : ?>
                                    <li>
                                        <a href="/logout"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-md">Logout</a>
                                        <?php else : ?>
                                        <a href="/login"
                                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-1.5 rounded-md">Login</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li><a>Navbar Item 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="drawer-side">
        <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-base-200 min-h-full w-80 p-4">
            <!-- Sidebar content here -->
            <li><a>Sidebar Item 1</a></li>
            <li><a>Sidebar Item 2</a></li>
        </ul>
    </div>
</div>