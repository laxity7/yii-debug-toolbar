<?php if (!empty($callstack)) : ?>

<table data-ydtb-data-table>
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo YiiDebug::t('Method')?></th>
            <th nowrap="nowrap"><?php echo YiiDebug::t('Time (s)')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($callstack as $id=>$entry):?>
        <tr>
            <td data-ydtb-data-type="number"><?php echo $id; ?></td>
            <td data-ydtb-data-type="varchar"><?php echo $entry[0]; ?></td>
            <td data-ydtb-data-type="number"><?php echo sprintf('%0.6F',$entry[1]); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
<p>
    <?php echo YiiDebug::t('No cache request logged or profiling the cache is DISABLED.')?>
</p>
<?php endif; ?>