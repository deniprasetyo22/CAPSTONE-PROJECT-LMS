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
            <li class="font-semibold">Courses</li>
        </ul>
    </div>
</div>

<div class="flex justify-between gap-2 mb-2">
    <a href="<?= site_url('courses/add') ?>" class="btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add Course
    </a>


    <label class="input flex-grow">
        <i class="fa fa-search"></i>
        <form method="get" action="<?= site_url('admin/courses') ?>" class="form-inline">
            <input type="text" placeholder="Search" value="<?= $params->search ?>" name="search" />
        </form>
    </label>
</div>

<div class="overflow-x-auto rounded-box border border-gray-300">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Course Code</th>
                <th>Enrollment Code</th>
                <th>Name</th>
                <th>Description</th>
                <th>Expected Duration</th>
                <th>Level Name</th>
                <th>Created At</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)) : ?>
                <tr>
                    <td>No courses found</td>
                </tr>
            <?php else : ?>
                <?php $no = 1; ?>
                <?php foreach ($courses as $course) : ?>
                    <tr class="hover:bg-gray-200">
                        <td><?= $no++ ?></td>
                        <td><?= $course->code ?></td>
                        <td><?= $course->enrollment_code ?></td>
                        <td><?= $course->name ?></td>
                        <td><?= $course->description ?></td>
                        <td><?= $course->expected_duration ?> Months</td>
                        <td><?= $course->levelName ?></td>
                        <td><?= $course->created_at ?></td>
                        <td>
                            <a href="<?= site_url('courses/edit/' . $course->id) ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit mr-1"></i>
                            </a>
                        </td>
                        <td>
                            <form action="<?= site_url('courses/delete/' . $course->id) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-error btn-sm" onclick="return confirm('Are you sure to delete?')">
                                    <i class="fa fa-trash mr-1"></i>
                                </button>
                                </a>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="mt-4 flex justify-center">
    <?= $pager->links('courses', 'custom_pager') ?>
</div>


<?= $this->endSection() ?>