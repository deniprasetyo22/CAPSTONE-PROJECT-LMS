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
                <!-- Teachers List -->
                <h2 class="text-lg font-semibold mb-2">Teachers</h2>
                <ul class="mb-6">
                    <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://i.pravatar.cc/100?img=1" />
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold">Mr. John Doe</p>
                            <p class="text-sm text-gray-500">john.doe@example.com</p>
                        </div>
                    </li>
                    <!-- Tambah teacher lainnya di sini -->
                </ul>

                <!-- Students List -->
                <h2 class="text-lg font-semibold mb-2">Students</h2>
                <ul>
                    <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://i.pravatar.cc/100?img=2" />
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold">Jane Smith</p>
                            <p class="text-sm text-gray-500">jane.smith@example.com</p>
                        </div>
                    </li>
                    <!-- Tambah student lainnya di sini -->
                </ul>
            </div>
        </div>



    </div>
</div>

<?= $this->endSection() ?>