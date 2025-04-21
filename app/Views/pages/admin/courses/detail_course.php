<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Detail Course<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<!-- name of each tab group should be unique -->
<div class="tabs tabs-box">
    <input type="radio" name="my_tabs_6" class="tab" aria-label="Tab 1" />
    <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 1</div>

    <input type="radio" name="my_tabs_6" class="tab" aria-label="Tab 2" checked="checked" />
    <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 2</div>

    <input type="radio" name="my_tabs_6" class="tab" aria-label="People" />
    <div class="tab-content bg-base-100 border border-base-300 p-6 rounded-box shadow-md">

        <div class="flex flex-col items-center">
            <div class="w-full max-w-2xl">
                <!-- Teachers Heading with Add Icon -->
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold">Teachers</h2>
                    <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>
                <!-- Teachers List -->
                <ul class="mb-6">
                    <?php foreach ($lecturers as $lecturer) : ?>
                        <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://i.pravatar.cc/100?img=1" />
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold"><?= $lecturer->first_name ?> <?= $lecturer->last_name ?></p>
                                <p class="text-sm text-gray-500"><?= $lecturer->email ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Students Heading with Add Icon -->
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold">Students</h2>
                    <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>

                <!-- Students List -->
                <ul>
                    <?php foreach ($students as $student) : ?>
                        <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://i.pravatar.cc/100?img=2" />
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold"><?= $student->first_name ?> <?= $student->last_name ?></p>
                                <p class="text-sm text-gray-500"><?= $student->email ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>