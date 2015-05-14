<?php
class DbConnection extends CDbConnection {
    private $_lastActive = 0;
    /**
     * @var mixed a positive integer as the timeout of trying to reconnect. set to false or 0 to disable this feature.
     */
    public $autoReconnect = 2;

    public function setActive($value) {
        if($value && $this->autoReconnect) {
            $lifetime = time() - $this->_lastActive;
            if($lifetime > intval($this->autoReconnect)) {
                try {
                    if($this->getActive()) {
                        @$this->getPdoInstance()->query('SELECT 1');
                    }
                } catch(Exception $e) {
                    parent::setActive(false);
                }
            }
        }
        parent::setActive($value);
        $this->_lastActive = time();
    }

} 
