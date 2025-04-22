<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<!-- Breadcrumbs -->
<div class="bg-gray-200 rounded-md px-4">
    <div class="breadcrumbs mb-6">
        <ul>
            <li><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="font-semibold">Course Level</li>
        </ul>
    </div>
</div>

<div class="mb-4">
    <?php if(session()->has('success')) : ?>
    <div role="alert" class="alert alert-success">
        <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
    </div>
    <?php endif ?>
</div>

<div class="flex flex-col gap-2 mb-2">
    <div class="flex gap-2 md:flex-grow">
        <div>
            <a href="<?= url_to('create_level') ?>" class="btn btn-primary">
                <i class="fa fa-plus mr-2"></i>Add Course Level
            </a>
        </div>
    </div>
</div>



<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($course_level)) : ?>
            <tr>
                <td colspan="7" class="text-center">No course level found</td>
            </tr>
            <?php else : ?>
            <?php $no = 1; ?>
            <?php foreach($course_level as $level) : ?>
            <tr class="hover:bg-gray-200">
                <td><?= $no++ ?></td>
                <td><?= $level->id ?></td>
                <td><?= $level->name ?></td>
                <td><?= $level->description ?></td>
                <td class="flex gap-2">
                    <a href="<?= url_to('edit_level', $level->id) ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit text-white"></i>
                    </a>
                    <button type="button" class="btn btn-error btn-sm text-white"
                        onclick="document.getElementById('deleteModal<?= $level->id ?>').showModal()">
                        <i class="fa fa-trash"></i>
                    </button>
                    <dialog id="deleteModal<?= $level->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this level?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_level', $level->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>