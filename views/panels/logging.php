 <?php
$colors=array(
    CLogger::LEVEL_PROFILE=>'#DFFFE0',
    CLogger::LEVEL_INFO=>'#FFFFDF',
    CLogger::LEVEL_WARNING=>'#FFDFE5',
    CLogger::LEVEL_ERROR=>'#FFC0CB',
);
?>

<div data-ydtb-panel-data="<?php echo $this->id ?>">
    <div>
        <table data-ydtb-data-table>
            <thead>
                <tr>
                    <th><?php echo YiiDebug::t('Message (details)')?></th>
                    <th><?php echo YiiDebug::t('Level')?></th>
                    <th><?php echo YiiDebug::t('Category')?></th>
                    <th><?php echo YiiDebug::t('Time')?></th>
                    <th>Duration<br />
                    <?php foreach($this->colorsDuration as $item) :?>
                    <span style="background-color:<?php echo $item['backgroundColor']; ?>; width:9px; height:9px; display:block; float:left; margin:1px;"></span>
                    <?php endforeach; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php $old=null; ?>
            <?php foreach($logs as $id=>$entry): ?>
            <?php $r=$this->diffTime($entry[3], $old); $old=$entry[3]; ?>
                <tr>
                    <td data-ydtb-data-type="varchar"><?php echo nl2br($entry[0]) ?></td>
                    <td data-ydtb-data-type="char"><?php echo $entry[1]; ?></td>
                    <td data-ydtb-data-type="char"><?php echo $entry[2] ?></td>
                    <td data-ydtb-data-type="number"><?php echo date('H:i:s.',$entry[3]).sprintf('%06d',(int)(($entry[3]-(int)$entry[3])*1000000));?></td>
                    <td style="color:<?php echo $r['textColor']; ?>; background-color:<?php echo $r['backgroundColor']; ?>;"><?php echo $r['duration']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

