<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= $page_title ?>
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>

<div>
    <!-- Breadcrumbs -->
    <div class="bg-gray-200 rounded-md px-4">
        <div class="breadcrumbs mb-6">
            <ul>
                <li><a href="<?= url_to('admin_dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url_to('list_courses') ?>">Discussions</a></li>
                <li class="font-semibold">Create Discussions</li>
            </ul>
        </div>
    </div>


    <div class="space-y-6 max-w-3xl mx-auto p-4">
        <!-- Main Post -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="text-xl font-bold mb-2">Discussion Topic: <?= $discussion->topic ?></h2>
                <p class="text-sm text-gray-500 mb-4">Posted by <span class="font-semibold">Teacher</span> • 3 hours ago</p>
                <p class="text-base">
                    <?= $discussion->description ?>
                </p>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">Comments</h3>

            <!-- Scrollable Container -->
            <div class="max-h-64 overflow-y-auto space-y-4 pr-2">

                <!-- Single Comment -->
                <?php foreach ($discussions_users as $discussion_user) : ?>
                    <div class="card bg-base-100 shadow-sm">
                        <div class="card-body flex flex-row gap-4">
                            <div class="avatar">
                                <div class="w-10 h-10 rounded-full">
                                    <img src="https://i.pravatar.cc/100?img=12" alt="User Avatar" />
                                </div>
                            </div>
                            <div>
                                <div class="font-semibold"><?= $discussion_user->first_name ?> <?= $discussion_user->last_name ?> <span class="text-xs text-gray-400 ml-2"><?= $discussion_user->timeAgo ?></span></div>
                                <p class="text-sm mt-1">
                                    <?= $discussion_user->content ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Add Comment Form -->
        <div class="mt-6">
            <form action="<?= base_url('discussion-comment/' . $discussion->id) ?>" method="post" id="courseRegistrationForm">
                <fieldset class="mb-4">
                    <textarea name="content"
                        data-pristine-required
                        data-pristine-required-message="Please enter a comment"
                        class="textarea textarea-bordered w-full <?= (session('errors.content')) ? 'border-red-500' : '' ?>" rows="3" placeholder="Add a comment..."></textarea>
                </fieldset>

                <div class="flex justify-end mt-2">
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </div>
            </form>
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

<?= $this->endSection() ?>