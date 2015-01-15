<?php if (!empty($summary)) : ?>
<table data-ydtb-data-table="fixed">
	<tbody>
		<tr>
			<th><?php echo YiiDebug::t('Total') ?></th>
			<td><?php echo $summary['count'] ?></td>
			<td><?php printf('%0.6F', $summary['time']) ?> s</td>
		</tr>
		<tr>
			<th><?php echo YiiDebug::t('Hit') ?> / <?php echo YiiDebug::t('Miss') ?></th>
			<td><?php echo $summary['hit'] ?> / <?php echo $summary['miss'] ?></td>
			<td><?php printf('%0.6F', $summary['hit']!=0 ? ($summary['miss'] / $summary['hit'] * 100) : 0) ?> %</td>
		</tr>
		<tr>
			<th>get</th>
			<td><?php echo $summary['get'] ?></td>
			<td><?php printf('%0.6F', $summary['get_time']) ?> s</td>
		</tr>
		<tr>
			<th>multy get</th>
			<td><?php echo $summary['mget'] ?></td>
			<td><?php printf('%0.6F', $summary['mget_time']) ?> s</td>
		</tr>
		<tr>
			<th>set</th>
			<td><?php echo $summary['set'] ?></td>
			<td><?php printf('%0.6F', $summary['set_time']) ?> s</td>
		</tr>
		<tr>
			<th>delete</th>
			<td><?php echo $summary['delete'] ?></td>
			<td><?php printf('%0.6F', $summary['delete_time']) ?> s</td>
		</tr>
		<tr>
			<th>flush</th>
			<td><?php echo $summary['flush'] ?></td>
			<td><?php printf('%0.6F', $summary['flush_time']) ?> s</td>
		</tr>
	</tbody>
</table>
<?php else : ?>
	<p>
		<?php echo YiiDebug::t( 'No cache request logged or profiling the cache is DISABLED.')?>
	</p>
<?php endif; ?>
