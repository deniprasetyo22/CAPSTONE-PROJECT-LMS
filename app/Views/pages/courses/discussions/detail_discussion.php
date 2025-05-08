<?php if (in_groups('student')) : ?>
<?= $this->extend('layouts/main_layout') ?>
<?php elseif (in_groups(['teacher', 'administrator'])) : ?>
<?= $this->extend('layouts/admin_layout') ?>
<?php endif ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?php if (in_groups('student')) : ?>
<?= $this->section('content') ?>
<?php elseif (in_groups(['teacher', 'administrator'])) : ?>
<?= $this->section('admin_content') ?>
<?php endif ?>

<div>
    <?php if (in_groups('teacher')) : ?>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('show_course', $discussion->course_id) ?>">Courses</a></li>
                <li class="font-semibold">Discussion</li>
            </ul>
        </div>
    </div>
    <?php endif ?>

    <div class="container mx-auto my-4">
        <div class="card card-lg border border-gray-300 shadow-lg w-full p-6">
            <div class="mb-4 text-blue-500 hover:text-blue-600 hover:underline">
                <a href="<?= url_to('show_course', $discussion->course_id) ?>">
                    <i class="fa fa-arrow-left mr-2"></i>Back
                </a>
            </div>
            <!-- Main Post -->
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="text-xl font-bold mb-2">Discussion Topic: <?= $discussion->topic ?></h2>
                    <p class="text-sm text-gray-500 mb-4">Posted by <span
                            class="font-semibold"><?= $discussion->first_name ?> <?= $discussion->last_name ?> </span> •
                        <?= $discussion->timeAgo ?> </p>
                    <p class="text-base">
                        <?= $discussion->description ?>
                    </p>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="space-y-4 mt-6">
                <h3 class="text-lg font-semibold">Comments</h3>

                <!-- Scrollable Container -->
                <div class="max-h-64 overflow-y-auto space-y-4 pr-2">

                    <!-- Single Comment -->
                    <?php foreach ($discussions_users as $discussion_user) : ?>
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body flex flex-row gap-4">
                            <div class="avatar">
                                <div class="w-10 h-10 rounded-full">
                                    <img src="<?= base_url('images/default_profile_picture.png') ?>"
                                        alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold"><?= $discussion_user->first_name ?>
                                    <?= $discussion_user->last_name ?> <span
                                        class="text-xs text-gray-400 ml-2"><?= $discussion_user->timeAgo ?></span></div>
                                <p class="text-sm mt-1">
                                    <?= $discussion_user->content ?>
                                </p>
                            </div>
                            <?php if ($discussion_user->isCurrentUser) : ?>
                            <div class="dropdown dropdown-end ml-auto">
                                <label tabindex="0" class="btn btn-ghost btn-sm btn-circle">
                                    <i class="fas fa-ellipsis-v"></i>
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content menu menu-sm bg-base-100 shadow rounded-box w-40">
                                    <li>
                                        <a data-id="<?= esc($discussion_user->id, 'attr') ?>"
                                            data-content="<?= esc($discussion_user->content, 'attr') ?>" href="#"
                                            class="edit-btn">Edit</a>
                                    </li>
                                    <li>
                                        <button
                                            onclick="document.getElementById('deleteModalComment<?= $discussion_user->id ?>').showModal()"
                                            class="w-full text-left">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <?php endif ?>
                        </div>
                    </div>
                    <dialog id="deleteModalComment<?= $discussion_user->id ?>" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg text-red-600">Delete Confirmation</h3>
                            <p class="py-4">Are you sure you want to delete this comment?</p>
                            <div class="modal-action">
                                <form method="dialog">
                                    <button class="btn btn-error text-white">Cancel</button>
                                </form>
                                <form action="<?= url_to('delete_comment_discussion', $discussion_user->id) ?>"
                                    method="post">
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


            <!-- Add Comment Form -->
            <?php if (in_groups(['student', 'teacher'])) : ?>
            <div class="mt-6">
                <form action="<?= url_to('add_comment_discussion', $discussion->id) ?>" method="post"
                    id="courseRegistrationForm">
                    <?= csrf_field() ?>
                    <div id="methodOverrideContainer"></div>
                    <fieldset class="mb-4">
                        <textarea name="content" id="contentTextarea" data-pristine-required
                            data-pristine-required-message="Please enter a comment"
                            class="textarea textarea-bordered w-full <?= (session('errors.content')) ? 'border-red-500' : '' ?>"
                            rows="3" placeholder="Add a comment..."></textarea>
                    </fieldset>

                    <div class="flex justify-end mt-2 gap-2">
                        <button type="button" id="cancelEditBtn" class="btn btn-secondary hidden">Cancel</button>
                        <button id="buttonSubmit" type="submit" class="btn btn-primary">Post Comment</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('courseRegistrationForm');
    const pristine = new Pristine(form, {
        classTo: 'mb-4',
        errorTextParent: 'mb-4',
        errorTextClass: 'text-red-500 text-sm'
    });

    form.addEventListener('submit', function(e) {
        if (!pristine.validate()) {
            e.preventDefault();
        }
    });
});
</script>

<script>
const form = document.getElementById('courseRegistrationForm');
const contentTextarea = document.getElementById('contentTextarea');
const submitBtn = document.getElementById('buttonSubmit');
const cancelBtn = document.getElementById('cancelEditBtn');
const methodInputContainer = document.getElementById('methodOverrideContainer');
// Cari semua tombol edit
document.querySelectorAll('.edit-btn').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault(); // Biar link gak nge-refresh halaman
        var content = this.getAttribute('data-content'); // Ambil data-content
        var commentId = this.getAttribute('data-id'); // Ambil data-id
        contentTextarea.value = content; // Isi ke textarea

        // Ubah form action ke endpoint update
        form.action = '<?= base_url('courses/discussion/discussion-comment') ?>/' + commentId;

        // Tambah atau update input hidden untuk method spoofing
        methodInputContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        // Ubah teks tombol submit
        submitBtn.innerText = 'Edit Comment';
        // tampilkan tombol cancel
        cancelBtn.classList.remove('hidden');
    });
});

cancelBtn.addEventListener('click', function() {
    // Kosongkan textarea
    contentTextarea.value = '';

    // Kembalikan action ke mode post
    form.action = '<?= base_url('discussion-comment/' . $discussion->id) ?>';

    // Hapus method PUT
    methodInputContainer.innerHTML = '';

    // Ubah teks tombol submit
    submitBtn.innerText = 'Post Comment';

    // Sembunyikan tombol cancel
    cancelBtn.classList.add('hidden');
});
</script>

<?= $this->endSection() ?>