<?php
/**
 * YiiDebugToolbarPanelCache class file.
 *
 * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
 */


/**
 * YiiDebugToolbarPanelCache class.
 *
 * YiiDebugToolbarPanelCache prepare data to render cache panel:
 *  * Cache settings
 *  * Profiling with requests time
 *  * Summary/Statistics
 *
 * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
 * @version $Id$
 * @package
 */
class YiiDebugToolbarPanelCache extends YiiDebugToolbarPanel
{
    public $i = 'i';

    private $_cache;

    public function  __construct($owner = null)
    {
        parent::__construct($owner);

        try {
            $cache = Yii::app()->cache;
            if (!($cache instanceof YiiDebugCacheProxy))
            {
                $this->_cache = false;
            }
        } catch (Exception $e) {
            $this->_cache = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuTitle()
    {
        return YiiDebug::t('Cache');
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSubTitle($f=4)
    {
        if (false !== $this->_cache) {
            /** @var YiiDebugCacheProxy $cache */
            $cache = Yii::app()->cache;
            $stats = $cache->getStats();
            return $stats['hit'] . ($stats['hit'] > 0 ? ('/'. vsprintf('%0.'.$f.'F', $stats['time']) . 's') : '');
        }
        return YiiDebug::t('No cache usage');
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        if (false !== $this->_cache)
        {
            return YiiDebug::t('Cache requests');
        }
        return YiiDebug::t('No cache usage');
    }

    /**
     * {@inheritdoc}
     */
    public function getSubTitle()
    {
        return  false !== $this->_cache
                ?  ('(' . self::getMenuSubTitle(6) . ')')
                : null;
    }

    /**
     * Initialize panel
     */
    public function init()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (false === $this->_cache) {
           return;
        }

        $logs = $this->filterLogs();
        $this->render('cache', array(
            'settings'  => $this->getCacheComponents(),
            'summary'   => $this->processSummary(),
            'callstack' => $this->processCallstack($logs)
        ));
    }

    private function duration($secs)
    {
        $vals = array(
            'w' => (int) ($secs / 86400 / 7),
            'd' => $secs / 86400 % 7,
            'h' => $secs / 3600 % 24,
            'm' => $secs / 60 % 60,
            's' => $secs % 60
        );
        $result = array();
        $added = false;
        foreach ($vals as $k => $v)
        {
            if ($v > 0 || false !== $added)
            {
                $added = true;
                $result[] = $v . $k;
            }
        }
        return implode(' ', $result);
    }

    private function getCacheComponents()
    {
         return Yii::app()->cache;
    }


    /**
     * Processing callstack.
     *
     * @param array $logs Logs.
     * @return array
     */
    protected function processCallstack(array $logs)
    {
        if (empty($logs))
        {
            return $logs;
        }

        $stack   = array();
        $results = array();
        $n       = 0;

        foreach ($logs as $log)
        {
            if(CLogger::LEVEL_PROFILE !== $log[1])
                continue;

            $message = $log[0];

            if (0 === strncasecmp($message,'begin:',6))
            {
                $log[0]  = substr($message,6);
                $log[4]  = $n;
                $stack[] = $log;
                $n++;
            }
            else if (0 === strncasecmp($message, 'end:', 4))
            {
                $token = substr($message,4);
                if(null !== ($last = array_pop($stack)) && $last[0] === $token)
                {
                    $delta = $log[3] - $last[3];
                    $results[$last[4]] = array($token, $delta, count($stack));
                }
                else
                    throw new CException(Yii::t('yii-debug-toolbar',
                            'Mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.',
                            array('{token}' => $token)
                        ));
            }
        }
        // remaining entries should be closed here
        $now = microtime(true);
        while (null !== ($last = array_pop($stack))){
            $results[$last[4]] = array($last[0], $now - $last[3], count($stack));
        }

        ksort($results);
        return $results; // array_map(array($this, 'formatLogEntry'), $results);
    }

    /**
     * Processing summary.
     *
     * @param array $logs Logs.
     * @return array
     */
    protected function processSummary()
    {
        /** @var YiiDebugCacheProxy $cache */
        $cache = Yii::app()->cache;
        return $cache->getStats();
    }

    /**
     * Aggregates the report result.
     *
     * @param array $result log result for this code block
     * @param float $delta time spent for this code block
     * @return array
     */
    protected function aggregateResult($result, $delta)
    {
        list($token, $calls, $min, $max, $total) = $result;

        switch (true)
        {
            case ($delta < $min):
                $min = $delta;
                break;
            case ($delta > $max):
                $max = $delta;
                break;
            default:
                // nothing
                break;
        }

        $calls++;
        $total += $delta;

        return array($token, $calls, $min, $max, $total);
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
            if (CLogger::LEVEL_PROFILE === $entry[1] && 0 === strpos($entry[2], YiiDebugCacheProxy::LOG_CATEGORY))
            {
                $logs[] = $entry;
            }
        }
        return $logs;
    }

}
