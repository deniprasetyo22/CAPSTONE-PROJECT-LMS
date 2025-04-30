<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto mb-4 px-4 md:px-0">

    <div class="divider mt-10">
        <div class="divider-title"><?= $page_title ?></div>
    </div>

    <div class="mb-4">
        <?php if (session()->has('success')) : ?>
            <div role="alert" class="alert alert-success">
                <span><i class="fa fa-check mr-2"></i> <?= session('success') ?></span>
            </div>
        <?php endif ?>
        <?php if (session()->has('error')) : ?>
            <div role="alert" class="alert alert-error">
                <span><i class="fa fa-xmark mr-2"></i> <?= session('error') ?></span>
            </div>
        <?php endif ?>
    </div>

    <div class="mb-4 flex justify-end">
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
                    <button class="btn">Cancel</button>
                </form>

                <form action="<?= url_to('leave_course', $enrollment->id) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-error">Yes, Leave</button>
                </form>
            </div>
        </div>
    </dialog>

    <div class="tabs tabs-box">
        <!-- Tab 1 -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Material" checked="checked" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <div class="space-y-4">
                <?php foreach ($courseContents as $content): ?>
                    <div class="card card-sm bg-base-100 border border-gray-300 hover:bg-base-200">
                        <div class="card-body flex-row items-start gap-4">
                            <!-- Icon -->
                            <div class="avatar avatar-placeholder">
                                <div class="bg-primary text-neutral-content w-10 rounded-full">
                                    <?php if ($content->content_type == 'Material') : ?>
                                        <i class="fas fa-file-alt"></i>
                                    <?php elseif ($content->content_type == 'Assignment') : ?>
                                        <i class="fas fa-tasks"></i>
                                    <?php elseif ($content->content_type == 'Quiz') : ?>
                                        <i class="fas fa-question-circle"></i>
                                    <?php endif ?>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow">
                                <h2 class="font-medium text-base">
                                    <a href="<?= url_to('show_course_detail', $course->id, $content->id) ?>"
                                        class="hover:text-blue-600">
                                        Teacher posted a new <?= $content->content_type ?>:
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
                                    <li><a href="<?= $content->content_url ?>" target="_blank">Open</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- tabs 3 for Assignment -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Assignment" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
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
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- tabs 4 for Discussion -->
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Discussion" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
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
                                    <a href="<?= site_url('discussion/' . $discussion->id) ?>"
                                        target="_blank" class="hover:text-blue-600">
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
                                        <a href="<?= site_url('discussion/' . $discussion->id) ?>"
                                            target="_blank">Open</a>
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
                                    <form action="<?= site_url('discussion/' . $discussion->id) ?>" method="post">
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

    </div>
</div>

<?= $this->endSection() ?>