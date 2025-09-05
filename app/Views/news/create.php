<?php

use Config\Services;

?>
<h2><?= esc($title); ?></h2>



<form action="/news/create" method="post">
	<?= csrf_field() ?>

	<label for="title">Title</label>
	<input type="text" name="title" /><br />
	
	<label for="body">Text</label>
	<textarea name="body"></textarea><br />
	
	<input type="submit" name="submit" value="Create news item" />
	
	<?= Services::validation()->listErrors() ?>
</form>