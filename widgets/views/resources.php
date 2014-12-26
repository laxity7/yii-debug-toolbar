<ul data-ydtb-dashboard>
	<?php foreach ($data as $entry) : ?>
	<li title="<?=$entry['title']?>">
		<i data-ydtb-icon="<?php echo $entry['i'] ?>"></i>
		<em><?php echo $entry['value'] ?></em>
		<small><?php echo $entry['unit'] ?></small>
	</li>
	<?php endforeach; ?>
</ul>