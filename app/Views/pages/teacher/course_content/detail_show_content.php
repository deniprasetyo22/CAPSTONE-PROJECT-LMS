<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-start gap-4 mb-4">
        <div class="avatar">
            <div class="w-12 rounded-full bg-primary text-white flex items-center justify-center">
                <i class="fas fa-file-pdf text-xl"></i>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-semibold"><?= esc($courseContent->title) ?></h1>
            <p class="text-sm text-gray-500">
                <?= esc($course->name) ?> • <?= date('j M Y', strtotime($courseContent->created_at)) ?>
            </p>
            <p class="text-sm text-gray-400 mt-1">By <?= esc($courseContent->created_by ?? 'Unknown') ?></p>
        </div>
    </div>

    <div class="flex justify-end mb-4">
        <a href="<?= site_url('file-form/' . $courseContent->id) ?>" class="btn bg-blue-200">
            <i class="fa fa-plus mr-2"></i>Add Files
        </a>
    </div>

    <!-- PDF Viewer -->
    <div class="flex flex-col md:flex-row md:justify-between md:space-x-4 space-y-4 md:space-y-0">
        <?php foreach ($files as $file) : ?>
            <div class="w-full md:w-1/2">
                <div class="card card-side bg-base-100 shadow-sm border border-gray-200">
                    <div class="card-body">
                        <div class="card-body flex flex-row items-center justify-between">
                            <h2 class="card-title">
                                <a href="<?= site_url('file-material/' . $file->encodedUrl) ?>" target="_blank" class="hover:text-blue-600">
                                    <span class="font-semibold"> <?= $file->fileName ?></span>
                                </a>
                            </h2>
                            <div class="dropdown dropdown-end ml-auto">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a href=<?= site_url('file-material/' . $file->encodedUrl)  ?>
                                            target="_blank">Open</a>
                                    </li>
                                    <li>
                                        <a href=<?= site_url('file-form-edit/' . $file->id)  ?>>Edit</a>
                                    </li>
                                    <li>
                                        <button class="w-full text-left"
                                            onclick="document.getElementById('deleteModalFile<?= $file->id ?>').showModal()">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <dialog id="deleteModalFile<?= $file->id ?>" class="modal">
                    <div class="modal-box">
                        <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                        <p class="py-4">Are you sure you want to delete this file?</p>
                        <div class="modal-action">
                            <form method="dialog">
                                <button class="btn btn-error text-white">Cancel</button>
                            </form>
                            <form action="<?= site_url('file/' . $file->id) ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                            </form>
                        </div>
                    </div>
                </dialog>
            </div>
        <?php endforeach; ?>
    </div>


</div>

<?= $this->endSection() ?>