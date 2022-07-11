<?php

namespace DBSessionStorage\SaveHandler;

use Laminas\Session\SaveHandler\DbTableGateway;

class EncodedDbTableGateway extends DbTableGateway {
    
    /**
     * Writes session data
     *
     * @param string $id
     * @param string $data
     *
     * @return bool
     */
    public function write($id, $data) : bool {

        $data = base64_encode($data);
        $data = [
            $this->options->getModifiedColumn() => time(),
            $this->options->getDataColumn()     => (string) $data
        ];

        $rows = $this->tableGateway->select([
            $this->options->getIdColumn()   => $id,
            $this->options->getNameColumn() => $this->sessionName
        ]);

        if ($rows->current()) {
            return (bool) $this->tableGateway->update($data, [
                $this->options->getIdColumn()   => $id,
                $this->options->getNameColumn() => $this->sessionName,
            ]);
        }
        
        $data[$this->options->getLifetimeColumn()] = $this->lifetime;
        $data[$this->options->getIdColumn()]       = $id;
        $data[$this->options->getNameColumn()]     = $this->sessionName;

        return (bool) $this->tableGateway->insert($data);

    }//end of write
    
    /**
     * Reads session data
     *
     * @param string $id
     * @param bool   $destroyExpired
     *
     * @return string
     */
    public function read($id, $destroyExpired = true) : string {

        $rows = $this->tableGateway->select([
            $this->options->getIdColumn()   => $id,
            $this->options->getNameColumn() => $this->sessionName,
        ]);

        if ($row = $rows->current()) {
            if ($row->{$this->options->getModifiedColumn()} + $row->{$this->options->getLifetimeColumn()} > time()) {
              return base64_decode($row->{$this->options->getDataColumn()});
            }
            
            $this->destroy($id);
        }
        
        return '';

    }//end of read

}//end of EnableDbTableGateway