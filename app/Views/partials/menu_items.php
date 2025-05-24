<?php foreach ($items as $item): ?>
    <li>
        <a href="<?= esc($item['url']) ?>"><?= esc($item['title']) ?></a>
        <?php if (!empty($item['children'])): ?>
            <ul>
                <?= view('partials/menu_items', ['items' => $item['children']]) ?>
            </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
