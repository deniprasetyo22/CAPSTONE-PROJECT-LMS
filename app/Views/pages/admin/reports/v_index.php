<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<!-- Breadcrumbs -->
<div class="bg-gray-200 rounded-md px-4">
    <div class="breadcrumbs mb-6">
        <ul>
            <li class="font-semibold">Report</li>
        </ul>
    </div>
</div>

<div class="card bg-base-100 shadow-md border border-gray-200">
    <div class="card-body">
        <div class="mb-4">
            <label for="userCategory" class="block mb-2 text-sm font-bold text-gray-900">User List:</label>
            <form action="<?= url_to('export_users_pdf') ?>" method="post" target="_blank"
                class="flex items-center gap-2">
                <select name="userCategory" id="userCategory" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="">All Users</option>
                    <?php foreach ($userCategories as $category) : ?>
                    <option value="<?= $category->id?>"><?= $category->name ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white py-1.5 px-3 rounded flex items-center gap-1">
                    <i class="fa-solid fa-file-export"></i> Export
                </button>
            </form>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-900">Course List:</label>
            <form action="<?= url_to('export_courses_pdf') ?>" method="post" target="_blank"
                class="flex items-center gap-2">
                <select name="courseLevel" id="courseLevel" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="">All Level</option>
                    <?php foreach ($courseLevels as $level) : ?>
                    <option value="<?= $level->id?>"><?= $level->name ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white py-1.5 px-3 rounded flex items-center gap-1">
                    <i class="fa-solid fa-file-export"></i> Export
                </button>
            </form>
        </div>


    </div>
</div>

<?= $this->endSection() ?>