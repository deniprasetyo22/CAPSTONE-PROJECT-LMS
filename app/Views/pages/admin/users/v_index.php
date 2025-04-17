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
            <li class="font-semibold">Users</li>
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

<div class="flex justify-between gap-2 mb-2">
    <a href="<?= url_to('create_user') ?>" class="btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add User
    </a>

    <div class="flex-grow mb-4">
        <form method="get" action="<?= url_to('users') ?>" class="flex gap-2 w-full">
            <input type="text" name="search" placeholder="Search by ID, Email, Username or Name..."
                value="<?= esc($params->search) ?>" class="input input-bordered flex-grow" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div>
        <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-info text-white">Reset</a>
    </div>
</div>


<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($users)) : ?>
            <tr>
                <td colspan="7" class="text-center">No users found</td>
            </tr>
            <?php else : ?>
            <?php $no = 1; ?>
            <?php foreach($users as $user) : ?>
            <tr class="hover:bg-gray-200">
                <td><?= $no++ ?></td>
                <td><?= $user->id ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->first_name . ' ' . $user->last_name ?></td>
                <td><?= $user->created_at ?></td>
                <td class="flex gap-2">
                    <a href="<?= url_to('show_user', $user->id) ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-eye text-white"></i>
                    </a>
                    <a href="<?= url_to('edit_user', $user->id) ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-edit text-white"></i>
                    </a>
                    <button type="button" class="btn btn-error btn-sm text-white"
                        onclick="document.getElementById('deleteModal<?= $user->id ?>').showModal()">
                        <i class="fa fa-trash"></i>
                    </button>
                    <dialog id="deleteModal<?= $user->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this user?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_user', $user->id) ?>" method="post">
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
    <div class="flex justify-center mt-2">
        <?= $pager->links('users', 'custom_pager') ?>
    </div>
    <div class="text-center mt-2">
        <small>Menampilkan <?= count($users) ?> dari <?= $total ?>
            total data (Halaman <?= $params->page ?>)</small>
    </div>
</div>

<?= $this->endSection() ?>