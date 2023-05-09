<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination">
		<?php if ($pager->hasPrevious()) : ?>
			<li class='page-link'>
				<a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('&#8676;') ?>">
					<span aria-hidden="true" title="Primeira Página"><?= lang('&#8676;') ?></span>
				</a>
			</li>
			<li class='page-link'>
				<a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('<') ?>">
					<span aria-hidden="true" title="Voltar 3 Páginas"><?= lang('<') ?></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li <?= $link['active'] ? 'class="active"' : '' ?>>
				<a class='page-link' href="<?= $link['uri'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class='page-link'>
				<a href="<?= $pager->getNext() ?>" aria-label="<?= lang('>') ?>">
					<span aria-hidden="true"title="Pular 3 Páginas"><?= lang('>') ?></span>
				</a>
			</li>
			<li class='page-link'>
				<a href="<?= $pager->getLast() ?>" aria-label="<?= lang('&#8677;') ?>">
					<span aria-hidden="true" title="Última Página"><?= lang('&#8677;') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
