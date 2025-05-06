<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto mb-4">

    <div class="p-4 md:px-0">
        <?php if(session()->has('success')) : ?>
        <div role="alert" class="alert alert-success">
            <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
        </div>
        <?php endif ?>
        <?php if(session()->has('error')) : ?>
        <div role="alert" class="alert alert-error">
            <span><i class="fa fa-xmark mr-2"></i> <?= session('error') ?></span>
        </div>
        <?php endif ?>
    </div>

    <div class="divider px-4 md:px-0 mb-10">
        <h1 class="text-2xl font-semibold divider-title">Profile</h1>
    </div>

    <div class="card lg:card-side bg-base-100 border border-gray-200 mx-auto">
        <figure class="p-6">
            <div class="avatar">
                <div class="w-64 rounded ring ring-primary ring-offset-base-100 ring-offset-2">
                    <img src="<?= url_to('view_profile_picture', $student->id, $student->profile_picture) ?>"
                        alt="Profile Picture" />
                </div>
            </div>
        </figure>
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold text-primary">
                <?= esc($student->first_name . ' ' . $student->last_name) ?></h2>

            <div class="space-y-2">
                <p><i class="fa fa-user mr-2 text-info"></i> <span class="font-semibold">Username:</span>
                    <?= esc($student->username) ?></p>
                <p><i class="fa fa-envelope mr-2 text-info"></i> <span class="font-semibold">Email:</span>
                    <?= esc($student->email) ?></p>
                <p><i class="fa fa-phone mr-2 text-info"></i> <span class="font-semibold">Phone:</span>
                    <?= esc($student->phone) ?></p>
                <p><i class="fa fa-venus-mars mr-2 text-info"></i> <span class="font-semibold">Gender:</span>
                    <?= esc($student->sex) ?></p>
                <p><i class="fa fa-calendar-alt mr-2 text-info"></i> <span class="font-semibold">Date of Birth:</span>
                    <?= date('d M Y', strtotime($student->dob)) ?></p>
                <p><i class="fa fa-map-marker-alt mr-2 text-info"></i> <span class="font-semibold">Address:</span>
                    <?= esc($student->address) ?></p>
                <p><i class="fa fa-id-badge mr-2 text-info"></i> <span class="font-semibold">Role:</span>
                    <span class="badge badge-primary badge-outline"><?= ucfirst($student->role_name) ?></span>
                </p>
            </div>

            <div class="card-actions justify-end mt-4">
                <a href="<?= url_to('edit_student_profile') ?>" class="btn btn-primary">
                    <i class="fa fa-edit mr-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>