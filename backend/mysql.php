<?php
class MySql {

    private $_host = 'localhost';
    private $_user = 'root';
    private $_password = '';
    private $_db = 'qrbind';
    private $_connection = null;

    private function _connect()
    {
        if(!$this->_connection) {
            $this->_connection = mysql_connect($this->_host, $this->_user, $this->_password);
            if (!$this->_connection) return false;
            if (!mysql_select_db($this->_db, $this->_connection)) return false;
        }
        return $this->_connection;
    }

    public function disconnect()
    {
        if($this->_connection) {
            mysql_close($this->_connection);
        }
    }

    public function select($query)
    {
        $this->_connect();
        if(!$this->_connection) return false;
        $result = mysql_query($query, $this->_connection);
        if (!$result) return false;
        $data = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($data,$row);
        }
        return $data;
    }

    public function insert($query)
    {
        $this->_connect();
        if(!$this->_connection) return false;
        $result = mysql_query($query, $this->_connection);
        return $result;
    }

    public function update($query)
    {
        $this->_connect();
        if(!$this->_connection) return false;
        $result = mysql_query($query, $this->_connection);
        return $result;
    }

    public function delete($query)
    {
        $this->_connect();
        if(!$this->_connection) return false;
        $result = mysql_query($query, $this->_connection);
        return $result;
    }
}