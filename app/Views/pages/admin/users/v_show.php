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
            <li><a href="<?= base_url('admin/users/index') ?>">Users</a></li>
            <li class="font-semibold">User Details</li>
        </ul>
    </div>
</div>

<div class="mb-4">
    <div class="card card-sm border border-gray-300">
        <div class="card-body">
            <div>
                <a href="<?= url_to('users') ?>" class="link link-primary">
                    <i class="fa fa-arrow-left"></i> back
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">User Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td><?= $user->id ?></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?= $user->first_name . ' ' . $user->last_name ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $user->email ?></td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td><?= $user->username ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?= $user->phone ?></td>
                        </tr>
                        <tr>
                            <th>Sex</th>
                            <td><?= $user->sex ?></td>
                        </tr>
                        <tr>
                            <th>DOB</th>
                            <td><?= $user->dob ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?= $user->address ?></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?= $user->created_at ?></td>
                        </tr>
                        <tr>
                            <th>Profile Picture</th>
                            <td>
                                <img src="<?= base_url( $user->profile_picture) ?>" alt="Profile Picture" width="50"
                                    height="50">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>