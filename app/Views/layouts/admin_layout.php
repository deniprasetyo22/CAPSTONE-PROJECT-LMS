<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="drawer lg:drawer-open h-full">
    <input id="admin-drawer" type="checkbox" class="drawer-toggle" />

    <!-- Main Content Area -->
    <div class="drawer-content flex flex-col">
        <!-- Mobile Topbar -->
        <div class="lg:hidden bg-gray-800 text-white flex items-center justify-between p-4">
            <h1 class="text-lg font-semibold">Admin Panel</h1>
            <label for="admin-drawer" class="btn btn-ghost text-white text-2xl">
                <i class="fas fa-bars"></i>
            </label>
        </div>

        <!-- Main Page Content -->
        <main class="flex-grow p-6 h-full overflow-auto">
            <?= $this->renderSection('admin_content') ?>
        </main>
    </div>

    <!-- Sidebar -->
    <div class="drawer-side">
        <label for="admin-drawer" class="drawer-overlay"></label>
        <?= $this->include('partials/sidebar') ?>
    </div>
</div>
<?= $this->endSection() ?>