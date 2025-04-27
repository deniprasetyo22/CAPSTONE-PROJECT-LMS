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

    <form action="<?= $baseUrl ?>" method="get" class="space-y-4 px-4 md:px-0">

        <div class="w-full">
            <div class="flex rounded-md shadow-sm">
                <input type="text" name="search" value="<?= $params->search ?>"
                    class="input input-bordered w-full rounded-l-md focus:outline-none"
                    placeholder="Search by Code or Name">
                <button type="submit" class="btn btn-primary rounded-r-md">Search</button>
            </div>
        </div>

        <div class="flex flex-wrap gap-5">
            <div class="w-full md:w-2/10">
                <select name="level" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="">All Level</option>
                    <?php foreach ($level as $l): ?>
                    <option value="<?= $l->id ?>" <?= ($params->level == $l->id) ? 'selected' : '' ?>>
                        <?= ucfirst($l->name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="perPage" class="select select-bordered w-full" onchange="this.form.submit()">
                    <?php foreach ([4, 8, 12, 36] as $perPage): ?>
                    <option value="<?= $perPage ?>" <?= ($params->perPage == $perPage) ? 'selected' : '' ?>>
                        <?= $perPage ?> / page
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="sort" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="">Sort By</option>
                    <option value="code" <?= ($params->sort == 'code') ? 'selected' : '' ?>>Code</option>
                    <option value="name" <?= ($params->sort == 'name') ? 'selected' : '' ?>>name</option>
                </select>
            </div>

            <div class="w-full md:w-2/10">
                <select name="order" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="asc" <?= ($params->order == 'asc') ? 'selected' : '' ?>>Ascending</option>
                    <option value="desc" <?= ($params->order == 'desc') ? 'selected' : '' ?>>Descending</option>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <a href="<?= $params->getResetUrl($baseUrl) ?>" class="btn btn-secondary w-full">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <?php if (empty($myCourses)) : ?>
    <div class="p-4 md:px-0">
        <div class="divider">
            <div class="divider-title">No Courses</div>
        </div>
    </div>
    <?php endif ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-4 md:px-0">
        <?php foreach ($myCourses as $myCourse): ?>
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <figure class="py-20 bg-blue-200">
                <span class="text-xl font-semibold"><?= esc($myCourse->name) ?></span>
            </figure>
            <div class="card-body">
                <h2 class="card-title text-lg font-semibold text-primary">
                    <?= esc($myCourse->code) ?> - <?= esc($myCourse->name) ?>
                </h2>
                <p class="text-sm text-gray-600"><?= esc($myCourse->description) ?></p>
                <div class="mt-4 text-sm text-gray-500">
                    <p><strong>Duration:</strong> <?= esc($myCourse->expected_duration) ?> months</p>
                    <p><strong>Level:</strong> <?= esc($myCourse->levelName) ?></p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <a href="<?= url_to('show_course', $myCourse->id) ?>">
                        <button class="btn btn-sm btn-primary">View</button>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-center mt-2">
        <?= $pager->links('myCourses', 'custom_pager') ?>
    </div>
    <div class="text-center mt-2">
        <small>Displaying <?= count($myCourses) ?> out of <?= $total ?> total courses (Page
            <?= $params->page ?>)</small>
    </div>
</div>

<?= $this->endSection() ?>