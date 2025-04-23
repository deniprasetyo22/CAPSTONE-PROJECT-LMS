<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mx-auto mb-4">

    <div class="card bg-blue-200 mt-4">
        <div class="card-body">
            <div class="flex justify-center py-4 border-b border-gray-300">
                <h2 class="card-title"><?= $course->name ?></h2>
            </div>
        </div>
    </div>

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

    <!-- name of each tab group should be unique -->
    <div class="tabs tabs-box">
        <input type="radio" name="my_tabs_6" class="tab" aria-label="Material" checked="checked" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <div class="space-y-4">
                <?php foreach ($courseContents as $content): ?>
                <div class="card card-sm bg-base-100 border border-gray-300 hover:bg-base-200">
                    <div class="card-body flex-row items-start gap-4">
                        <!-- Icon -->
                        <div class="avatar avatar-placeholder">
                            <div class="bg-primary text-neutral-content w-10 rounded-full">
                                <?php if($content->content_type == 'Material') : ?>
                                <i class="fas fa-file-alt"></i>
                                <?php elseif($content->content_type == 'Assignment') : ?>
                                <i class="fas fa-tasks"></i>
                                <?php elseif($content->content_type == 'Quiz') : ?>
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

        <input type="radio" name="my_tabs_6" class="tab" aria-label="Tab 2" />
        <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 2</div>
    </div>
</div>

<?= $this->endSection() ?>