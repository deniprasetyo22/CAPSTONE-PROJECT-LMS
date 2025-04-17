<?php $pager->setSurroundCount(1) ?>

<nav aria-label="Page navigation">
    <ul class="inline-flex items-center -space-x-px text-sm">
        <?php if ($pager->hasPrevious()) : ?>
        <li>
            <a class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700"
                href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                <?= lang('Pager.first') ?>
            </a>
        </li>
        <li>
            <a class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                href="<?= $pager->getPreviousPage() ?>" aria-label="<?= lang('Pager.previous') ?>">
                <?= lang('Pager.previous') ?>
            </a>
        </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
        <li>
            <a class="flex items-center justify-center px-3 h-8 border border-gray-300 
                    <?= $link['active'] ? 'bg-blue-500 text-white font-bold' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700' ?>"
                href="<?= $link['uri'] ?>">
                <?= $link['title'] ?>
            </a>
        </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
        <li>
            <a class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                href="<?= $pager->getNextPage() ?>" aria-label="<?= lang('Pager.next') ?>">
                <?= lang('Pager.next') ?>
            </a>
        </li>
        <li>
            <a class="flex items-center justify-center px-3 h-8 text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700"
                href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                <?= lang('Pager.last') ?>
            </a>
        </li>
        <?php endif ?>
    </ul>
</nav>