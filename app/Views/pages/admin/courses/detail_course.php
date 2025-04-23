<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>Detail Course<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div class="mb-4">
    <?php if (session()->has('error')) : ?>
        <div role="alert" class="alert alert-error mb-4">
            <span><i class="fa fa-xmark mr-2"></i><?= session('error') ?></span>
        </div>
    <?php endif ?>
</div>

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
                    <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1" onclick="openInviteModalLecturers()">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                    <dialog id="my_modal_5" class="modal">
                        <div class="modal-box w-11/12 max-w-xl">
                            <h3 class="text-lg font-bold">Invite Teachers!</h3>

                            <!-- Label & Select di luar form -->
                            <label class="block mb-2 text-sm font-medium">Search User</label>
                            <select id="user-select-lecturer" name="user_lecturer_id" style="width: 100%"></select>

                            <!-- Tombol-tombol sejajar -->
                            <div class="modal-action">
                                <!-- FORM hanya wrap tombol Invite -->
                                <form action="<?= site_url('add_lecturer_course/' . $course->id) ?>" method="post">
                                    <!-- Hidden input untuk user_id -->
                                    <input type="hidden" name="user_lecturer_id" id="hidden-user-lecturer-id">
                                    <button class="btn" type="submit">Invite</button>
                                </form>

                                <!-- Close tetap dialog -->
                                <form method="dialog">
                                    <button class="btn">Close</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                </div>

                <!-- Teachers List -->
                <ul>
                    <?php foreach ($lecturers as $lecturer) : ?>
                        <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://i.pravatar.cc/100?img=2" />
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold"><?= $lecturer->first_name ?> <?= $lecturer->last_name ?></p>
                                <p class="text-sm text-gray-500"><?= $lecturer->email ?></p>
                            </div>
                            <button class="ml-auto btn btn-sm btn-ghost text-primary flex items-center gap-1" onclick="document.getElementById('deleteModal<?= $lecturer->id ?>').showModal()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <dialog id="deleteModal<?= $lecturer->id ?>" class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                    <p class="py-4">Are you sure you want to remove this teacher?</p>
                                    <div class="modal-action">
                                        <form method="dialog">
                                            <button class="btn btn-error text-white">Cancel</button>
                                        </form>
                                        <form action="<?= site_url('remove_lecturer_course/' . $lecturer->id) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Students Heading with Add Icon -->
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold">Students</h2>
                    <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1" onclick="openInviteModal()">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                    <dialog id="my_modal_4" class="modal">
                        <div class="modal-box w-11/12 max-w-xl">
                            <h3 class="text-lg font-bold">Invite Students!</h3>

                            <!-- Label & Select di luar form -->
                            <label class="block mb-2 text-sm font-medium">Search User</label>
                            <select id="user-select" name="user_id" style="width: 100%"></select>

                            <!-- Tombol-tombol sejajar -->
                            <div class="modal-action">
                                <!-- FORM hanya wrap tombol Invite -->
                                <form action="<?= site_url('enroll_student/' . $course->id) ?>" method="post">
                                    <!-- Hidden input untuk user_id -->
                                    <input type="hidden" name="user_id" id="hidden-user-id">
                                    <button class="btn" type="submit">Invite</button>
                                </form>

                                <!-- Close tetap dialog -->
                                <form method="dialog">
                                    <button class="btn">Close</button>
                                </form>
                            </div>
                        </div>

                    </dialog>

                </div>
                <!-- Students List -->
                <ul class="mb-6">
                    <?php foreach ($students as $student) : ?>
                        <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://i.pravatar.cc/100?img=1" />
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold"><?= $student->first_name ?> <?= $student->last_name ?></p>
                                <p class="text-sm text-gray-500"><?= $student->email ?></p>
                            </div>
                            <button class="ml-auto btn btn-sm btn-ghost text-primary flex items-center gap-1" onclick="document.getElementById('deleteModalStudent<?= $student->id ?>').showModal()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <dialog id="deleteModalStudent<?= $student->id ?>" class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                    <p class="py-4">Are you sure you want to remove this student?</p>
                                    <div class="modal-action">
                                        <form method="dialog">
                                            <button class="btn btn-error text-white">Cancel</button>
                                        </form>
                                        <form action="<?= site_url('remove_student_course/' . $student->id) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function openInviteModal() {
        const modal = document.getElementById("my_modal_4");
        modal.showModal();

        // Clear sebelumnya dulu
        $('#user-select').empty().val(null).trigger('change');

        // Destroy biar gak dobel init
        if ($.fn.select2 && $('#user-select').hasClass("select2-hidden-accessible")) {
            $('#user-select').select2('destroy');
        }

        // Init Select2
        $('#user-select').select2({
            dropdownParent: $('#my_modal_4'),
            placeholder: 'Search users by name or email',
            allowClear: true,
            minimumInputLength: 1,
            delay: 250,
            ajax: {
                url: '<?= base_url('/search-users') ?>',
                dataType: 'json',
                cache: false,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(user => ({
                            id: user.id,
                            text: `${user.first_name} ${user.last_name} (${user.email})`
                        }))
                    };
                }
            }
        });

        // Clear handler
        $('#user-select').off('select2:clear').on('select2:clear', function() {
            $('#user-select').val(null).trigger('change'); // Clear value
            $('#user-select').empty(); // Hapus semua opsi biar tidak tersisa
        });

        // Select handler
        $('#user-select').off('select2:select').on('select2:select', function(e) {
            const selectedData = e.params.data;
            console.log('Selected:', selectedData);
        });

        $('#user-select').on('select2:select', function(e) {
            const selectedData = e.params.data;
            $('#hidden-user-id').val(selectedData.id);
        });
    }

    function openInviteModalLecturers() {
        const modal = document.getElementById("my_modal_5");
        modal.showModal();

        // Clear sebelumnya dulu
        $('#user-select-lecturer').empty().val(null).trigger('change');

        // Destroy biar gak dobel init
        if ($.fn.select2 && $('#user-select-lecturer').hasClass("select2-hidden-accessible")) {
            $('#user-select-lecturer').select2('destroy');
        }

        // Init Select2
        $('#user-select-lecturer').select2({
            dropdownParent: $('#my_modal_5'),
            placeholder: 'Search users by name or email',
            allowClear: true,
            minimumInputLength: 1,
            delay: 250,
            ajax: {
                url: '<?= base_url('/search-users') ?>',
                dataType: 'json',
                cache: false,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(user => ({
                            id: user.id,
                            text: `${user.first_name} ${user.last_name} (${user.email})`
                        }))
                    };
                }
            }
        });

        // Clear handler
        $('#user-select-lecturer').off('select2:clear').on('select2:clear', function() {
            $('#user-select-lecturer').val(null).trigger('change'); // Clear value
            $('#user-select-lecturer').empty(); // Hapus semua opsi biar tidak tersisa
        });

        // Select handler
        $('#user-select-lecturer').off('select2:select').on('select2:select', function(e) {
            const selectedData = e.params.data;
            $('#hidden-user-lecturer-id').val(selectedData.id);
        });
    }
</script>



<?= $this->endSection() ?>