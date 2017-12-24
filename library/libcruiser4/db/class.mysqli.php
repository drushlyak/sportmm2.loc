<?php
    class mysqli {
        /**
        * @var resource
        * @access private
        */ 
     	var $connection;
        
        var $connection_errno = 0;
        
        /**
        * @desc Gets the number of affected rows in a previous MySQL operation
        * @var mixed
        * @access public
        */
        var $affected_rows;
        
        /**
        * @desc Retrieves information about the most recently executed query
        * @var string
        * @access public
        */
        var $info;
        
        /**
        * @desc Returns the auto generated id used in the last query
        * @var int
        * @access public
        */
        var $insert_id;
        
        /**
        * @desc Returns a string description of the last error
        * @var string
        * @access public
        */
        var $error;
        
        /**
        * @desc Returns the error code for the most recent function call
        * @var int
        * @access public        
        */
        var $errno;
        
        /**
         * Возвращает текстовое сообщение об ошибке
         *
         * @var unknown_type
         */
        var $connection_error;
        
        /**
        * @desc Open a new connection to the MySQL server
        * @constructor
        */
        function mysqli($host, $username, $password, $database, $options = array()) {
            if (! function_exists("mysql_connect")) {
            	$this->connection_error = "Mysql extension not available";
            	return;
            }
        	
        	$this->connection = @mysql_connect($host, $username, $password);
            if ($e = mysql_errno()) {
                $this->connection_errno = $e;
                $this->connection_error = mysql_error();
                return;
            }
            
            mysql_select_db($database, $this->connection);
            if ($e = mysql_errno()) {
                $this->connection_errno = $e;
                $this->connection_error = mysql_error();
                return;
            }
        }
        
        /**
        * @desc Закрывает ранее открытое соединение
        * @return bool
        */
        function close() {
            if ($this->connection) 
            	return mysql_close($this->connection);
            return false;
        }

        /**
        * @desc Performs a query on the database
        * @access public
        * @param $query   string
        * @return mixed
        */
        function query($query) {
            $resource = mysql_query($query, $this->connection);
            $this->error = mysql_error($this->connection);
            $this->errno = mysql_errno($this->connection);
            
//            $this->info = mysql_info($this->connection);
            $this->affected_rows = mysql_affected_rows($this->connection);            
            if (is_bool($resource)) {
                $this->insert_id = mysql_insert_id($this->connection);

                return $resource;
            } else 
                return new mysql_result($resource);
        }
        
		/**
		 * Меняет базу данных на указанную
		 *
		 * @param string $dbname - имя новой базы данных
		 * @return unknown
		 */
        function select_db($dbname) {
            $b = mysql_select_db($dbname, $this->connection);
            $this->errno = mysql_errno($this->connection);
            $this->error = mysql_error($this->connection);
            return $b;
        }
    }
    
    class mysql_result {
        var $resource;
        
        /**
        * @desc Gets the number of rows in a result
        * @var int
        */
        var $num_rows;
        
        function mysql_result($resource) {
            $this->resource = $resource;
            $this->num_rows = mysql_num_rows($this->resource);
        }
        
        function close() {
            mysql_free_result($this->resource);
        }
        
        function fetch_row() {
            return mysql_fetch_row($this->resource);
        }
        
        function fetch_object() {
            return mysql_fetch_object($this->resource);
        }
        
        function fetch_assoc() {
            return mysql_fetch_assoc($this->resource);
        }
        
        function fetch_array($resulttype = MYSQL_NUM) {
            return mysql_fetch_array($this->resource, $resulttype);
        }
        
        function field_count() {
        }
    }
?>