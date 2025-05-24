<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<h2>Menu Access Management</h2>

	<?php if (session()->getFlashdata('success')): ?>
	    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
	<?php endif; ?>

	<?php foreach ($menus as $menu): ?>
	    <form method="post" action="<?= base_url('admin/menu/save-access') ?>">
	        <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
	        <div style="margin-bottom: 1em; border: 1px solid #ccc; padding: 10px;">
	            <strong><?= esc($menu['title']) ?></strong><br>

	            <?php foreach ($groups as $group): ?>
	                <label>
	                    <input type="checkbox" name="group_ids[]"
	                        value="<?= $group['id'] ?>"
	                        <?= in_array($group['id'], $menuGroupMap[$menu['id']] ?? []) ? 'checked' : '' ?>>
	                    <?= esc($group['name']) ?>
	                </label>
	            <?php endforeach; ?>

	            <div>
	                <button type="submit">Save</button>
	            </div>
	        </div>
	    </form>
	<?php endforeach; ?>

</body>
</html>