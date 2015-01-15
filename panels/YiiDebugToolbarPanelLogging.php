<?php
/**
 * YiiDebugToolbarPanelLogging class file.
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 */


/**
 * YiiDebugToolbarPanelLogging represents an ...
 *
 * Description of YiiDebugToolbarPanelLogging
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 * @author Igor Golovanov <igor.golovanov@gmail.com>
 * @author Nabi KaramAliZadeh <info@nabi.ir>
 * @version $Id$
 * @package YiiDebugToolbar
 * @since 1.1.7
 */
class YiiDebugToolbarPanelLogging extends YiiDebugToolbarPanel
{
	public $i = 'j';
	
    /**
     * Message count.
     *
     * @var integer
     */
    private $_countMessages;

    /**
     * Logs.
     *
     * @var array
     */
    private $_logs;
    
    /**
     * Colors and max number of durations
     * NOTE: all max numbers must be desc
     * @author Nabi KaramAliZadeh <info@nabi.ir>
     * 
     * @var array
     */
    public $colorsDuration = array(
		array(
			'textColor'=>'#fff',
			'backgroundColor'=>'#c00',//red
			'maxNumber'=>1,
		),
		array(
			'textColor'=>'#000',
			'backgroundColor'=>'#f60',//orange
			'maxNumber'=>0.1,
		),
		array(
			'textColor'=>'#000',
			'backgroundColor'=>'#ff0',//yellow
			'maxNumber'=>0.01,
		),
		array(
			'textColor'=>'#000',
			'backgroundColor'=>'#3c3',//green
			'maxNumber'=>0.001,
		),
		array(
			'textColor'=>'#000',
			'backgroundColor'=>'#3cf',//blue
			'maxNumber'=>0.0001,
		),
	);

    /**
     * {@inheritdoc}
     */
    public function getMenuTitle()
    {
        return YiiDebug::t('Logging');
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSubTitle()
    {
        return $this->countMessages;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return YiiDebug::t('Log Messages');
    }

    /**
     * Get logs.
     *
     * @return array
     */
    public function getLogs()
    {
        if (null === $this->_logs)
        {
            $this->_logs = $this->filterLogs();
        }
        return $this->_logs;
    }

    /**
     * Get count of messages.
     *
     * @return integer
     */
    public function getCountMessages()
    {
        if (null === $this->_countMessages)
        {
            $this->_countMessages = count($this->logs);
        }
        return $this->_countMessages;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->render('logging', array(
            'logs' => $this->logs
        ));
    }

    /**
     * Get filter logs.
     *
     * @return array
     */
    protected function filterLogs()
    {
        $logs = array();
        foreach ($this->owner->getLogs() as $entry)
        {            
            if (CLogger::LEVEL_PROFILE !== $entry[1] &&  false === strpos($entry[2], 'system.db.CDbCommand'))
            {
                $logs[] = $entry;
            }
        }
        return $logs;
    }
    
    /**
     * Calculate duration execute between two trace 
     * @author Nabi KaramAliZadeh <info@nabi.ir>
     * 
     * @param float $new
     * @param float $old
     * @return array
     */
    public function diffTime($new, $old)
    {
       	$duration = $new - $old;
    	$duration = ($old===null) ? '-' : sprintf('%06f', $duration);
    	if($duration != '-'){
    		foreach($this->colorsDuration as $item){
    			if($duration >= $item['maxNumber']){
    				$return = $item;
    				break;
    			}
    		}
    	}else{
    		$return['textColor'] = '#000';
    		$return['backgroundColor'] = '#fff';
    	}
    	$return['duration'] = $duration;
    	return $return;
    }
}
