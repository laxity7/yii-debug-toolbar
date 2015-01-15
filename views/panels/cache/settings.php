<?php if ($settings) : ?>
	<table data-ydtb-data-table="fixed">
		<tbody>
		<?php foreach ($settings as $key => $value) : ?>
			<tr>
				<th><?php echo $key; ?></th>
				<td><?php echo $this->dump($value); ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
<?php else : ?>
	<?php echo YiiDebug::t('Cache not configured')?>
<?php endif;?>