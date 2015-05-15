<?php

use yii\db\Connection;

class HealingConnection extends Connection {

    public function createCommand($sql = null, $params = []){
        try {
            $comm = parent::createCommand('SHOW STATUS;');
            $command->execute();
        } catch (\Exception $e){
            if($this->getIsActive() === true){
                $this->close();
                $this->open();
            }
        }
        return parent::createCommand($sql, $params);
    }

}
