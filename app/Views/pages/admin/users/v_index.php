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

    <label class="input flex-grow">
        <i class="fa fa-search"></i>
        <input type="search" placeholder="Search" />
    </label>
</div>

<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Sex</th>
                <th>DOB</th>
                <th>Address</th>
                <th>Profile Picture</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($users)) : ?>
            <tr>
                <td>No users found</td>
            </tr>
            <?php else : ?>
            <?php $no = 1; ?>
            <?php foreach($users as $user) : ?>
            <tr class="hover:bg-gray-200">
                <td><?= $no++ ?></td>
                <td><?= $user->id ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->first_name ?></td>
                <td><?= $user->last_name ?></td>
                <td><?= $user->phone ?></td>
                <td><?= $user->sex ?></td>
                <td><?= $user->dob ?></td>
                <td><?= $user->address ?></td>
                <td>
                    <img src="<?= base_url( $user->profile_picture) ?>" alt="Profile Picture" width="50" height="50">
                </td>
                <td><?= $user->created_at ?></td>
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