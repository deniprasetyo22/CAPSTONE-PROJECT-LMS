<?php if (in_groups('teacher')) : ?>
    <?= $this->extend('layouts/admin_layout') ?>
<?php elseif (in_groups('student')) : ?>
    <?= $this->extend('layouts/main_layout') ?>
<?php endif ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?php if (in_groups('teacher')) : ?>
    <?= $this->section('admin_content') ?>
<?php elseif (in_groups('student')) : ?>
    <?= $this->section('content') ?>
<?php endif ?>

<div class="container mx-auto mb-4">

    <?php if (in_groups('teacher')) : ?>
        <div class="bg-gray-200 rounded-md px-4">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= url_to('teacher_courses') ?>">Courses</a></li>
                    <li class="font-semibold">Detail Course</li>
                </ul>
            </div>
        </div>
    <?php endif ?>

    <div class="my-4">
        <?php if (session()->has('error')) : ?>
            <div role="alert" class="alert alert-error mb-4">
                <span><i class="fa fa-xmark mr-2"></i><?= session('error') ?></span>
            </div>
        <?php endif ?>
        <?php if (session()->has('success')) : ?>
            <div role="alert" class="alert alert-success">
                <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
            </div>
        <?php endif ?>
    </div>

    <div class="tabs tabs-box">
        <!-- tabs 1 for detail course -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Detail Course" checked="checked" />
        <div class="tab-content bg-base-100 border border-base-300 p-6 rounded-box">

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-primary"><?= esc($course->name) ?></h2>

                <div>
                    <span class="font-semibold">Course Code:</span>
                    <span class="badge badge-secondary ml-2"><?= esc($course->code) ?></span>
                </div>

                <div>
                    <span class="font-semibold">Enrollment Code:</span>
                    <span class="badge badge-success ml-2"><?= esc($course->enrollment_code) ?></span>
                </div>

                <div>
                    <span class="font-semibold">Description:</span>
                    <p class="mt-1 text-sm text-gray-700">
                        <?= esc($course->description) ?>
                    </p>
                </div>

                <div>
                    <span class="font-semibold">Expected Duration:</span>
                    <span class="badge badge-outline ml-2"><?= esc($course->expected_duration) ?> months</span>
                </div>

                <div>
                    <span class="font-semibold">Course Level:</span>
                    <span class="badge badge-primary ml-2"><?= esc($course->levelName) ?></span>
                </div>
            </div>

        </div>

        <!-- tabs 2 for list materials -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Material" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <?php if (in_groups('teacher')) : ?>
                <div class="flex justify-end mb-4">
                    <a href="<?= url_to('create_material', $course->id) ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus mr-2"></i>Add Material
                    </a>
                </div>
            <?php endif ?>

            <?php if (empty($courseContents)) : ?>
                <div class="p-4 md:px-0">
                    <div class="divider">
                        <div class="divider-title">No Materials</div>
                    </div>
                </div>
            <?php endif ?>

            <div class="space-y-4">
                <?php foreach ($courseContents as $content): ?>
                    <div class="card card-sm bg-base-100 border border-gray-300 hover:bg-base-200">
                        <div class="card-body flex-row items-start gap-4">
                            <!-- Icon -->
                            <div class="avatar avatar-placeholder">
                                <div class="bg-primary text-neutral-content w-10 rounded-full">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <h2 class="font-medium text-base">
                                    <a href="<?= url_to('show_material', $course->id, $content->id) ?>"
                                        class="hover:text-blue-600">
                                        <span class="font-semibold"><?= $content->title ?></span>
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    <?= date('j M', strtotime($content->created_at)) ?>
                                </p>
                            </div>

                            <!-- More options -->
                            <div class="dropdown dropdown-end ml-auto">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a href="<?= url_to('show_material', $course->id, $content->id) ?>">Open
                                        </a>
                                    </li>
                                    <?php if (in_groups('teacher')) : ?>
                                        <li>
                                            <a href="<?= url_to('edit_material', $content->id) ?>">Edit</a>
                                        </li>
                                        <li>
                                            <button
                                                onclick="document.getElementById('deleteModalContent<?= $content->id ?>').showModal()"
                                                class="w-full text-left">
                                                Delete
                                            </button>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        </div>
                        <dialog id="deleteModalContent<?= $content->id ?>" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                <p class="py-4">Are you sure you want to delete this content?</p>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn btn-error text-white">Cancel</button>
                                    </form>
                                    <form action="<?= url_to('delete_material', $content->id) ?>" method="post">
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

        <!-- tabs 3 for Assignment -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Assignment" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <?php if (in_groups('teacher')) : ?>
                <div class="flex justify-end mb-4">
                    <a href="<?= url_to('create_assignment', $course->id) ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus mr-2"></i>Add Assignment
                    </a>
                </div>
            <?php endif ?>

            <?php if (empty($assignments)) : ?>
                <div class="p-4 md:px-0">
                    <div class="divider">
                        <div class="divider-title">No Assignments</div>
                    </div>
                </div>
            <?php endif ?>

            <div class="space-y-4">
                <?php foreach ($assignments as $assignment): ?>
                    <div class="card card-sm bg-base-100 border border-gray-300 hover:bg-base-200">
                        <div class="card-body flex-row items-start gap-4">
                            <!-- Icon -->
                            <div class="avatar avatar-placeholder">
                                <div class="bg-primary text-neutral-content w-10 rounded-full">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <h2 class="font-medium text-base">
                                    <a href="<?= url_to('show_assignment', $assignment->id) ?>" class="hover:text-blue-600">
                                        <span class="font-semibold"><?= $assignment->title ?></span>
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    <?= date('j M', strtotime($assignment->created_at)) ?>
                                </p>
                            </div>

                            <!-- More options -->
                            <div class="dropdown dropdown-end ml-auto">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a href="<?= url_to('show_assignment', $assignment->id) ?>">Open</a>
                                    </li>
                                    <li>
                                        <a href="<?= url_to('edit_assignment', $assignment->id) ?>">Edit</a>
                                    </li>
                                    <li>
                                        <button
                                            onclick="document.getElementById('deleteModalAssignment<?= $assignment->id ?>').showModal()"
                                            class="w-full text-left">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <dialog id="deleteModalAssignment<?= $assignment->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this assignment?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_assignment', $assignment->id) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-success text-white">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- tabs 4 for Discussion -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Discussion" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <?php if (in_groups('teacher')) : ?>
                <div class="flex justify-end mb-4">
                    <a href="<?= url_to('create_discussion', $course->id) ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus mr-2"></i>Add Discussion
                    </a>
                </div>
            <?php endif ?>

            <?php if (empty($discussions)) : ?>
                <div class="p-4 md:px-0">
                    <div class="divider">
                        <div class="divider-title">No Discussions</div>
                    </div>
                </div>
            <?php endif ?>

            <div class="space-y-4">
                <?php foreach ($discussions as $discussion): ?>
                    <div class="card card-sm bg-base-100 border border-gray-300 hover:bg-base-200">
                        <div class="card-body flex-row items-start gap-4">
                            <!-- Icon -->
                            <div class="avatar avatar-placeholder">
                                <div class="bg-primary text-neutral-content w-10 rounded-full">
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <h2 class="font-medium text-base">
                                    <a href="<?= url_to('show_discussion', $discussion->id) ?>" class="hover:text-blue-600">
                                        Teacher posted a new discussion:
                                        <span class="font-semibold"><?= $discussion->topic ?></span>
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500">
                                    <?= date('j M', strtotime($discussion->created_at)) ?>
                                </p>
                            </div>

                            <!-- More options -->
                            <div class="dropdown dropdown-end ml-auto">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a href="<?= site_url('discussion/' . $discussion->id) ?>" target="_blank">Open</a>
                                    </li>
                                    <li>
                                        <a href="<?= url_to('edit_discussion', $discussion->id) ?>">Edit</a>
                                    </li>
                                    <li>
                                        <button
                                            onclick="document.getElementById('deleteModalDiscussion<?= $discussion->id ?>').showModal()"
                                            class="w-full text-left">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <dialog id="deleteModalDiscussion<?= $discussion->id ?>" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                <p class="py-4">Are you sure you want to delete this discussion?</p>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn btn-error text-white">Cancel</button>
                                    </form>
                                    <form action="<?= url_to('delete_discussion', $discussion->id) ?>" method="post">
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

        <!-- tabs 5 for list students and teachers -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="People" />
        <div class="tab-content bg-base-100 border border-base-300 p-6 rounded-box shadow-md">

            <div class="flex flex-col items-center">
                <div class="w-full max-w-2xl">

                    <!-- Teachers Heading with Add Icon -->
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold">Teachers</h2>
                        <?php if (in_groups('teacher')) : ?>
                            <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1"
                                onclick="openInviteModalLecturers()">
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
                                        <form action="<?= url_to('add_teacher_course', $course->id) ?>" method="post">
                                            <!-- Hidden input untuk user_id -->
                                            <input type="hidden" name="user_teacher_id" id="hidden-user-lecturer-id">
                                            <button class="btn" type="submit">Invite</button>
                                        </form>

                                        <!-- Close tetap dialog -->
                                        <form method="dialog">
                                            <button class="btn">Close</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        <?php endif ?>
                    </div>

                    <!-- Teachers List -->
                    <ul>
                        <?php foreach ($teachers as $teacher) : ?>
                            <li class="flex items-center gap-4 p-4 border rounded-lg mb-2">
                                <div class="avatar">
                                    <div class="w-12 rounded-full">
                                        <img src="https://i.pravatar.cc/100?img=2" />
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold"><?= $teacher->first_name ?> <?= $teacher->last_name ?></p>
                                    <p class="text-sm text-gray-500"><?= $teacher->email ?></p>
                                </div>
                                <?php if (in_groups('teacher')) : ?>
                                    <button class="ml-auto btn btn-sm btn-ghost text-primary flex items-center gap-1"
                                        onclick="document.getElementById('deleteModal<?= $teacher->id ?>').showModal()">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <dialog id="deleteModal<?= $teacher->id ?>" class="modal">
                                        <div class="modal-box">
                                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                                            <p class="py-4">Are you sure you want to remove this teacher?</p>
                                            <div class="modal-action">
                                                <form method="dialog">
                                                    <button class="btn btn-error text-white">Cancel</button>
                                                </form>
                                                <form action="<?= url_to('remove_teacher_course',  $teacher->id) ?>"
                                                    method="post">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-success text-white">Yes,
                                                        Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                <?php endif ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Students Heading with Add Icon -->
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-semibold">Students</h2>
                        <?php if (in_groups('teacher')) : ?>
                            <button class="btn btn-sm btn-ghost text-primary flex items-center gap-1"
                                onclick="openInviteModal()">
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
                                        <form action="<?= url_to('enroll_student', $course->id) ?>" method="post">
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
                        <?php endif ?>
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
                                <?php if (in_groups('teacher')) : ?>
                                    <button class="ml-auto btn btn-sm btn-ghost text-primary flex items-center gap-1"
                                        onclick="document.getElementById('deleteModalStudent<?= $student->id ?>').showModal()">
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
                                                <form action="<?= url_to('remove_student_course', $student->id) ?>"
                                                    method="post">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-success text-white">Yes,
                                                        Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </dialog>
                                <?php endif ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- tabs 6 Leave Button -->
        <?php if (in_groups('student')) : ?>
            <div class="flex items-center ml-auto">
                <button class="btn btn-sm bg-red-500 hover:bg-red-600 text-white" onclick="leave_modal.showModal()">
                    <i class="fa fa-sign-out"></i> Leave
                </button>
            </div>

            <dialog id="leave_modal" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Are you sure?</h3>
                    <p class="py-4">You are about to leave the course. This action cannot be undone.</p>
                    <div class="modal-action">
                        <form method="dialog">
                            <button class="btn btn-error text-white">Cancel</button>
                        </form>

                        <form action="<?= url_to('leave_course', $enrollment->id) ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-success text-white">Yes, Leave</button>
                        </form>
                    </div>
                </div>
            </dialog>
        <?php endif ?>
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
                url: '<?= url_to('search_users', $course->id, 'student') ?>',
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
                url: '<?= url_to('search_users', $course->id, 'teacher') ?> ?>',
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