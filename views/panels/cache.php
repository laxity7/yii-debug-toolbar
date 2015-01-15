<div data-ydtb-tabs="<?php echo $this->id?>">
    <ul>
        <li><a href="#cache-summary"><i data-ydtb-icon="s"></i><?php echo YiiDebug::t('Summary')?></a></li>
        <li><a href="#cache-callstack"><i data-ydtb-icon="s"></i><?php echo YiiDebug::t('Callstack')?></a></li>
        <li><a href="#cache-settings"><i data-ydtb-icon="s"></i><?php echo YiiDebug::t('Settings')?></a></li>
    </ul>
    <div data-ydtb-panel-data="<?php echo $this->id ?>">
            <div>
                <div data-ydtb-tab="cache-settings">
                    <?php $this->render('cache/settings', array(
                        'settings'=>$settings
                    )) ?>
                </div>
                <div data-ydtb-tab="cache-summary">
                    <?php $this->render('cache/summary', array(
                        'summary'=>$summary
                    )) ?>
                </div>
                <div data-ydtb-tab="cache-callstack">
                    <?php $this->render('cache/callstack', array(
                        'callstack'=>$callstack
                    )) ?>
                </div>
            </div>
    </div>
</div>
