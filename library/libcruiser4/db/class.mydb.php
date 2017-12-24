<?php
	
	define('DATE_FORMAT_DATE', 1);
	define('DATE_FORMAT_TIME', 2);
	define('DATE_FORMAT_DATETIME', 3);
	define('DATE_FORMAT_YEAR', 4);
	define('DATE_FORMAT_MONTH', 5);
	define('DATE_FORMAT_DAY', 6);
	
	require_once (LIB_PATH . "/db/sql_placeholder.php");
	
	/**
	 * @return mydb
	 */
	function & getDBInstance() {
		return mydb::instance();
	}
	
	class mydb extends mysqli {
		
		/**
		 * Options
		 * 	trigger_error = 1 	trigger standart PHP error mechanism on mysql errors
		 *
		 * @var unknown_type
		 */
		var $options = array();
	
		/**
		 * Log records list
		 *
		 * @var array
		 */
		var $log = array();
		var $total_time;
	
		/**
		 *
		 * @var int
		 */
		var $executed_queries;
	
		public function __construct($host, $username, $password, $database, $options = array()) {
			$starttime = microtime(1);
	
			$default_options = array(
				"trigger_error" => 1,
				"logging" => 0
			);
	
			foreach ($default_options as $option_name => $option_val) {
				if (key_exists($option_name, $options)) {
					$this->options[$option_name] = (bool) $options[$option_name];
					continue;
				}
				$this->options[$option_name] = $option_val;
			}
	
			//$this->mysqli($host, $username, $password, $database);
			parent::__construct($host, $username, $password, $database);
			//if ($this->connection_errno && $this->options['trigger_error']) {
			//	trigger_error('MySQL connection error: ', $this->connection_error());
			//}
	
			$time_transact = microtime(1) - $starttime;
			$this->total_time += $time_transact;
		}
	
	
		/**
		 * @static
		 * @return mydb
		 */
		static function & instance () {
			static $db = null;
			if (is_null($db)) {
				$db = @new mydb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

				if (@$db->ping() !== true) {
					print('<b>Ошибка подключения к БД!</b> Проверьте настройки подключения к базе данных.<br />');
					die('<b>DB connection error!</b> Check DB config.');
				}

				$version = substr($db->get_one("SELECT VERSION()"), 0, 1);
				if ($version >= 4) {
					$db->query("SET NAMES '".DB_CHARACTER."'");
				}
			}
			return $db;
		}
	
	
		function sql_placeholder() {
			$args = func_get_args();
			return call_user_func_array("sql_placeholder", $args);
		}
	
		function sql_pholder() {
			$args = func_get_args();
			return call_user_func_array("sql_pholder", $args);
		}
		
		/**
		 * @return array of result
		 */
		function query($query) {
			$starttime = microtime(1);
	
			if (func_num_args() > 1) {
				$args = func_get_args();
				$query = call_user_func_array(array(&$this, "sql_pholder"), $args);
	
				if (! $query) {
					return false;
				}
			}
	
			$ret = @parent::query($query);
	
			if ($this->error && $this->options["trigger_error"]) {
				trigger_error("MySQL said: $this->error; <br/>Query: $query", E_USER_WARNING);
			}
	
			$time_transact = microtime(1) - $starttime;
			$this->total_time += $time_transact;
			$this->executed_queries++;
	
			if ($this->options["logging"]) {
				$this->log[] = array($query, $time_transact, $this->affected_rows, $this->error);
			}
	
	
			return $ret;
		}
	
		/**
		 * Total recors count
		 *
		 * @param string $query
		 * 
		 * FIXME: Для запроса:
		 *	SELECT d.*
		 *		FROM delegates AS d
		 *			JOIN conferences AS c ON d.conference_id = c.id
		 *			JOIN delegates_type dt ON dt.id = d.id_delegate_type
		 *			JOIN countries cntr ON cntr.id = d.country_id
		 *	WHERE c.id IN (SELECT conference_id FROM `user_conferences` WHERE user_id = '54')
		 *	LIMIT 0, 15
		 *	возвращает FALSE из-за preg_replace("/SELECT\s(.*)\sFROM/is", "SELECT {$count_str} FROM", $query)
		 */
		function query_total($query, $count_str = "COUNT(*)") {
			/*
			 * первичная реализация не брала в расчет подзапросы типа:
			 *	SELECT d.*
			 *		FROM delegates AS d
			 *			JOIN conferences AS c ON d.conference_id = c.id
			 *			JOIN delegates_type dt ON dt.id = d.id_delegate_type
			 *			JOIN countries cntr ON cntr.id = d.country_id
			 *	WHERE c.id IN (SELECT conference_id FROM `user_conferences` WHERE user_id = '54')
			 *	LIMIT 0, 15
			 * a так же возможность агрегирования запроса:
			 *	SELECT exc.*,
			 *			GROUP_CONCAT(l.`text` SEPARATOR ' ') AS all_lng_value
			 *	FROM tmpl_executor_code AS exc
			 *		LEFT JOIN vcore_language AS l ON l.`name_value` = exc.`lngh_code`
			 *	WHERE exc.id_site_tmpl_page = '3' AND exc.id_executor = '11'
			 *	HAVING GROUP_CONCAT(l.`text` SEPARATOR ' ') LIKE '%стена%'
			 *	GROUP BY exc.`lngh_code`
			 */
			// $query = preg_replace("/SELECT\s(.*)\sFROM/is", "SELECT {$count_str} FROM", $query);
			$query = preg_replace("/ORDER\sBY(.*)\n/is", "", $query);
			$query = preg_replace("/LIMIT\s+\d+\s*,\s*\d+/is", "", $query);
			$query = "SELECT COUNT(*) FROM ({$query}) AS __countsubquery";

			return $this->get_one($query);
		}
	
		function multi_query ($sql) {
			$queries = explode(";", $sql);
			foreach ($queries as $q) {
				$this->query($q);
			}
		}
	
		function &select () {
			return new mydb_select($this);
		}
	
		/**
		 * @param string $sql
		 * @return unknown
		 */
		function get_row($sql) {
			$args = func_get_args();
			$rs = call_user_func_array(array(&$this, "query"), $args);
			if ($rs->num_rows) {
				$row = $rs->fetch_assoc();
				$rs->close();
				return $row;
			}
			return false;
		}
	
		/**
		 *
		 * @param unknown_type $sql
		 * @return unknown
		 */
		function get_one($sql) {
			$args = func_get_args();
			$row = call_user_func_array(array(&$this, "get_row"), $args);
			if ($row) {
				return array_shift($row);
			}
			return false;
		}
	
		/**
		 *
		 * @param string $sql
		 * @return unknown
		 */
		function get_all($sql) {
			$args = func_get_args();
			$rs = call_user_func_array(array(&$this, "query"), $args);
			if ($rs->num_rows) {
				$all = array();
				while ($row = $rs->fetch_assoc()) {
					$all[] = $row;
				}
				$rs->close();
	
				return $all;
			}
			return false;
		}
	
		/**
		 *
		 * @param unknown_type $sql
		 * @return unknown
		 */
		function get_hashtable($sql) {
			$args = func_get_args();
			$rs = call_user_func_array(array(&$this, "query"), $args);
			if ($rs->num_rows) {
				$hashtable = array();
				while ($row = $rs->fetch_assoc()) {
					$k = reset($row);
					$v = next($row);
					$hashtable[$k] = $v;
				}
				$rs->close();
				return $hashtable;
			}
			return false;
		}
	
		/**
		 *
		 * @param unknown_type $sql
		 * @return unknown
		 */
		function get_vector($sql) {
			$args = func_get_args();
			$rs = call_user_func_array(array(&$this, "query"), $args);
			if ($rs->num_rows) {
				$ret = array();
				while ($row = $rs->fetch_assoc()) {
					$ret[] = array_shift($row);
				}
				$rs->close();
	
				return $ret;
			}
			return false;
		}
	
		/**
		 * 
		 * @param $table
		 * @param $data
		 * @return unknown_type
		 * 
		 * TODO вставка структур типа NOW()
		 */
		function insert($table, $data) {
			$this->query("INSERT INTO `$table` SET ?%", $data);
			return $this->insert_id;
		}
	
		function update($table, $data, $identy) {
			return $this->query("UPDATE `$table` SET ?% WHERE ?*", $data, $identy);
		}
	
		function delete($table, $identy = '') {
			if ($identy != '') {
				return $this->query("DELETE FROM `$table` WHERE ?*", $identy);
			}
			return false;
		}
	
		function truncate($table) {
			return $this->query("DELETE FROM `$table`");
		}
	
		function save($table, $data, $identy) {
			$rs = $this->query("SELECT * FROM $table WHERE &*", $identy);
			if ($rs->num_rows) {
				return $this->update($table, $data, $identy);
			} else {
				return $this->insert($table, $data);
			}
		}
	
		function get_xml($sql) {
			$args = func_get_args();
			$data = call_user_func_array(array($this, "get_all"), $args);
	
			$res = "<xml>\r\n<recordset>\r\n";
			foreach ($data as $row) {
				$res .= "<item>\r\n";
				foreach ($row as $k => $m) {
					$res .= "<$k>$m</$m>\r\n";
				}
				$res .= "</item>\r\n";
			}
			$res .= "</recordset>\r\n</xml>";
	
			return $res;
		}
	
	
		function get_metadata($table) {
			$sql= "SHOW FIELDS FROM $table";
			$data = $this->get_all($sql);
			$res = array();
			foreach ($data as $row) {
				$res[array_shift($row)] = $row;
			}
			return $res;
		}
	
		/**
		 * Вызов оберток функций
		 *
		 * @param (string) $method
		 * @param (array) $arguments
		 * @return mixed
		 */
		public function __call($method, $arguments) {
			if (!method_exists($this, $method)) {

				$realMethod = substr($method, 0, -1);
				if (method_exists($this, $realMethod)) {
					if (count($arguments) > 1) {
						$sql = call_user_func_array(array(&$this, "sql_pholder"), $arguments);
					} else {
						$sql = $arguments[0];
					}

					$DB = debug_backtrace();
					fb("", "SQLINFO FROM " . basename($DB[1]['file']) . ":" . $DB[1]['line'] . " called method: {$realMethod}", FirePHP::GROUP_START);
					fb($sql, "SQL", FirePHP::INFO);

					$this->formatExplain($sql);

					// запрос
					$starttime = microtime(1);
					$data = call_user_func_array(array(&$this, $realMethod), $arguments);
					$time_transact = round(microtime(1) - $starttime, 4) * 10000;
					fb($data, "RESULT DATA", FirePHP::INFO);
					fb($time_transact . "ms", "TIME", FirePHP::INFO);
					fb("", FirePHP::GROUP_END);

					return $data;
				}
			}
		}

		/**
		 * Форматирование результата EXPLAIN запроса
		 *
		 * @param string $sql
		 */
		public function formatExplain($sql) {
			$explain = $this->get_all("EXPLAIN EXTENDED " . $sql);
			if (is_array($explain)) {
				$explainTable = array();

				if (is_array($explain)) {

					foreach ($explain[0] as $inx => $val) {
						$explainTable[0][] = $inx;
					}

					foreach ($explain as $i => $explData) {
						foreach ($explData as $inx => $val) {
							$explainTable[($i + 1)][] = $val;
						}
					}
				}
				fb($explainTable, "EXPLAIN SQL", FirePHP::TABLE);
			} else {
				fb($this->error, "MySQL said", FirePHP::ERROR);
			}
		}

		function prepare_date($timestamp, $field_type=DATE_FORMAT_DATETIME) {
			if (!$timestamp===false && $timestamp > 0) {
				switch ($field_type) {
					case DATE_FORMAT_DATE:
						return date('Y-m-d', $timestamp);
					case DATE_FORMAT_TIME:
						return date('H:i:s', $timestamp);
					case DATE_FORMAT_YEAR:
						return date('Y', $timestamp);
					case DATE_FORMAT_MONTH:
						return date('m', $timestamp);
					case DATE_FORMAT_DAY:
						return date('d', $timestamp);
					case DATE_FORMAT_DATETIME:
					default:
						return date('Y-m-d H:i:s', $timestamp);
				}
			}
			return false;
		}
	
		function fetch_log() {
			ob_start();
	
			$total_time = 0;
			$total_query = sizeof($this->log);
			$total_ok = 0;
			$total_error = 0;
			foreach ($this->log as $line) {
				$total_time += $line[1];
				$line[3] ? $total_error++ : $total_ok++;
			}
	
			$css_decl = array(
					"db_log" => "font-family: Arial, Helvetica, sans-serif; font-size: small; font-weight: normal; color: #000000;",
					"db_log th" => "font-weight: bold; color: #EEEEEE; background-color: #000077; padding: 4px;",
					"db_log td" => "background-color: #EEEEEE; border: 1px groove #777777; padding: 4px; height:10px;",
					"db_log td.query" => "font-family: 'Courier New', Courier, mono;",
					"db_log td.error_ok" => "text-align: center; vertical-align: middle; background-color: #008800;",
					"db_log td.error" => "background-color: #AA0000; vertical-align: middle;"
					);
					?>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="<?=$css_decl["db_log"]?>">
		<tr>
			<th width="70%" style="<?=$css_decl["db_log th"]?>">Query</th>
			<th width="10%" style="<?=$css_decl["db_log th"]?>">Time</th>
			<th width="10%" style="<?=$css_decl["db_log th"]?>">Rows</th>
			<th width="10%" style="<?=$css_decl["db_log th"]?>">Error</th>
		</tr>
	
		<?php
		foreach ($this->log as $line) {
			$error_style = $line[3] ? "error" : "error_ok";
			$error_msg = $line[3] ? $line[3] : "OK";
			$time = number_format($line[1], 4, ".", "");
			?>
		<tr>
			<td style="<?=$css_decl["db_log td"]." ".$css_decl["db_log td.query"]?>"><?=$line[0]?></td>
			<td style="<?=$css_decl["db_log td"]?>"><?=$time?></td>
			<td style="<?=$css_decl["db_log td"]?>"><?=$line[2]?></td>
			<td style="<?=$css_decl["db_log td"]." ".$css_decl["db_log td.$error_style"]?>"><?=$error_msg?></td>
		</tr>
		<?php
		}
		?>
	
		<tr>
			<td colspan="3"><pre style="font-weight: bold;">
	
	Total time: <?=number_format($total_time, 3, ".", "")?>     Total Queries: <?=$total_query?>    Success: <?=$total_ok?>    Fail: <?=$total_error?>
	
	</pre></td>
		</tr>
	</table>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
	
		return $ret;
		}
	
		function display_log () {
			print $this->fetch_log();
		}
	}
	
	
	class mydb_select {
	
		/**
		 * The component parts of a SELECT statement.
		 *
		 * @var array
		 */
		var $_parts = array(
			'distinct'		=> false,
			'forUpdate'		=> false,
			'cols'			=> array(),
			'from'			=> array(),
			'join'			=> array(),
			'where'			=> array(),
			'group'			=> array(),
			'having'		=> array(),
			'order'			=> array(),
			'limitCount'	=> null,
			'limitOffset'	=> null
		);
	
		/**
		 * Tracks which columns are being select from each table and join.
		 *
		 * @var array
		 */
		var $_tableCols = array();
	
		/**
		 * Db
		 *
		 * @var mydb
		 */
		var $_db;
	
		function __construct(&$db) {
			$this->_db = &$db;
		}
	
	
		/**
		 * Converts this object to an SQL SELECT string.
		 *
		 * @return string This object as a SELECT string.
		 */
		function toString() {
			// initial SELECT [DISTINCT] [FOR UPDATE]
			$sql = "SELECT";
			if ($this->_parts['distinct']) {
				$sql .= " DISTINCT";
			}
			if ($this->_parts['forUpdate']) {
				$sql .= " FOR UPDATE";
			}
			$sql .= "\n\t";
	
			// add columns
			if ($this->_parts['cols']) {
				$sql .= implode(",\n\t", $this->_parts['cols']) . "\n";
			}
	
			// from these tables
			if ($this->_parts['from']) {
				$sql .= "FROM ";
				$sql .= implode(", ", $this->_parts['from']) . "\n";
			}
	
			// joined to these tables
			if ($this->_parts['join']) {
				$list = array();
				foreach ($this->_parts['join'] as $join) {
					$tmp = '';
					// add the type (LEFT, INNER, etc)
					if (! empty($join['type'])) {
						$tmp .= strtoupper($join['type']) . ' ';
					}
					// add the table name and condition
					$tmp .= 'JOIN ' . $join['name'];
					$tmp .= ' ON ' . $join['cond'];
					// add to the list
					$list[] = $tmp;
				}
				// add the list of all joins
				$sql .= implode("\n", $list) . "\n";
			}
	
			// with these where conditions
			if ($this->_parts['where']) {
				$sql .= "WHERE\n\t";
				$sql .= implode("\n\t", $this->_parts['where']) . "\n";
			}
	
			// grouped by these columns
			if ($this->_parts['group']) {
				$sql .= "GROUP BY\n\t";
				$sql .= implode(",\n\t", $this->_parts['group']) . "\n";
			}
	
			// having these conditions
			if ($this->_parts['having']) {
				$sql .= "HAVING\n\t";
				$sql .= implode("\n\t", $this->_parts['having']) . "\n";
			}
	
			// ordered by these columns
			if ($this->_parts['order']) {
				$sql .= "ORDER BY\n\t";
				$sql .= implode(",\n\t", $this->_parts['order']) . "\n";
			}
	
			// determine count
			$count = ! empty($this->_parts['limitCount'])
			? (int) $this->_parts['limitCount']
			: 0;
	
			// determine offset
			$offset = ! empty($this->_parts['limitOffset'])
			? (int) $this->_parts['limitOffset']
			: 0;
	
			// add limits, and done
			if ($count > 0) {
				$sql .= "LIMIT $offset, $count";
			}
	
			return trim($sql);
		}
	
	
		/**
		 * Makes the query SELECT DISTINCT.
		 *
		 * @param bool $flag Whether or not the SELECT is DISTINCT (default true).
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function distinct($flag = true) {
			$this->_parts['distinct'] = (bool) $flag;
			return $this;
		}
	
	
		/**
		 * Makes the query SELECT FOR UPDATE.
		 *
		 * @param bool $flag Whether or not the SELECT is DISTINCT (default true).
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function forUpdate($flag = true) {
			$this->_parts['forUpdate'] = (bool) $flag;
			return $this;
		}
	
	
		/**
		 * Adds a FROM table and optional columns to the query.
		 *
		 * @param string $name The table name.
		 * @param array|string $cols The columns to select from this table.
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function from($name, $cols = '*') {
			// add the table to the 'from' list
			$this->_parts['from'] = array_merge(
			$this->_parts['from'],
			(array) $name
			);
	
			// add to the columns from this table
			$this->_tableCols($name, $cols);
			return $this;
		}
	
		/**
		 * Populate the {@link $_parts} 'join' key
		 *
		 * Does the dirty work of populating the join key.
		 *
		 * @access protected
		 * @param null|string $type Type of join; inner, left, and null are
		 * currently supported
		 * @param string $name Table name
		 * @param string $cond Join on this condition
		 * @param array|string $cols The columns to select from the joined table
		 * @return Zend_Db_Select This Zend_Db_Select object
		 */
		function _join($type, $name, $cond, $cols) {
			if (!in_array($type, array('left', 'inner'))) {
				$type = null;
			}
	
			$this->_parts['join'][] = array(
				'type' => $type,
				'name' => $name,
				'cond' => $cond
			);
	
			// add to the columns from this joined table
			$this->_tableCols($name, $cols);
			return $this;
		}
	
		/**
		 * Adds a JOIN table and columns to the query.
		 *
		 * @param string $name The table name.
		 * @param string $cond Join on this condition.
		 * @param array|string $cols The columns to select from the joined table.
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function join($name, $cond, $cols = null) {
			return $this->_join(null, $name, $cond, $cols);
		}
		
		/**
		 * Add a LEFT JOIN table and colums to the query
		 *
		 * @param string $name The table name.
		 * @param string $cond Join on this condition.
		 * @param array|string $cols The columns to select from the joined table.
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function joinLeft($name, $cond, $cols = null) {
			return $this->_join('left', $name, $cond, $cols);
		}
	
		/**
		 * Add an INNER JOIN table and colums to the query
		 *
		 * @param string $name The table name.
		 * @param string $cond Join on this condition.
		 * @param array|string $cols The columns to select from the joined table.
		 * @return Zend_Db_Select This Zend_Db_Select object.
		 */
		function joinInner($name, $cond, $cols = null) {
			return $this->_join('inner', $name, $cond, $cols);
		}
	
	
		/**
		 * Adds a WHERE condition to the query by AND.
		 *
		 * If a value is passed as the second param, it will be quoted
		 * and replaced into the condition wherever a question-mark
		 * appears. Array values are quoted and comma-separated.
		 *
		 * <code>
		 * // simplest but non-secure
		 * $select->where("id = $id");
		 *
		 * // secure (ID is quoted but matched anyway)
		 * $select->where('id = ?', $id);
		 *
		 * </code>
		 *
		 * @param string $cond The WHERE condition.
		 * @param string $val A single value to quote into the condition.
		 * @return void
		 */
		function where($cond) {
			if (func_num_args() > 1) {
				$val = func_get_arg(1);
				$cond = $this->_db->sql_placeholder($cond, $val);
			}
	
			if ($this->_parts['where']) {
				$this->_parts['where'][] = "AND $cond";
			} else {
				$this->_parts['where'][] = $cond;
			}
	
			return $this;
		}
	
	
		/**
		 * Adds a WHERE condition to the query by OR.
		 *
		 * Otherwise identical to where().
		 *
		 * @param string $cond The WHERE condition.
		 * @param string $val A value to quote into the condition.
		 * @return void
		 *
		 * @see where()
		 */
		function orWhere($cond) {
			if (func_num_args() > 1) {
				$val = func_get_arg(1);
				$cond = $this->_db->sql_placeholder($cond, $val);
			}
	
			if ($this->_parts['where']) {
				$this->_parts['where'][] = "OR $cond";
			} else {
				$this->_parts['where'][] = $cond;
			}
	
			return $this;
		}
	
	
		/**
		 * Adds grouping to the query.
		 *
		 * @param string|array $spec The column(s) to group by.
		 * @return void
		 */
		function group($spec) {
			if (is_string($spec)) {
				$spec = explode(',', $spec);
			} else {
				settype($spec, 'array');
			}
	
			foreach ($spec as $val) {
				$this->_parts['group'][] = trim($val);
			}
	
			return $this;
		}
	
	
		/**
		 * Adds a HAVING condition to the query by AND.
		 *
		 * If a value is passed as the second param, it will be quoted
		 * and replaced into the condition wherever a question-mark
		 * appears. See {@link where()} for an example
		 *
		 * @param string $cond The HAVING condition.
		 * @param string $val A single value to quote into the condition.
		 * @return void
		 */
		function having($cond) {
			if (func_num_args() > 1) {
				$val = func_get_arg(1);
				$cond = $this->_db->sql_placeholder($cond, $val);
			}
	
			if ($this->_parts['having']) {
				$this->_parts['having'][] = "AND $cond";
			} else {
				$this->_parts['having'][] = $cond;
			}
	
			return $this;
		}
	
	
		/**
		 * Adds a HAVING condition to the query by OR.
		 *
		 * Otherwise identical to orHaving().
		 *
		 * @param string $cond The HAVING condition.
		 * @param string $val A single value to quote into the condition.
		 * @return void
		 *
		 * @see having()
		 */
		function orHaving($cond) {
			if (func_num_args() > 1) {
				$val = func_get_arg(1);
				$cond = $this->_db->sql_placeholder($cond, $val);
			}
	
			if ($this->_parts['having']) {
				$this->_parts['having'][] = "OR $cond";
			} else {
				$this->_parts['having'][] = $cond;
			}
	
			return $this;
		}
	
	
		/**
		 * Adds a row order to the query.
		 *
		 * @param string|array $spec The column(s) and direction to order by.
		 * @return void
		 */
		function order($spec) {
			if (is_string($spec)) {
				$spec = explode(',', $spec);
			} else {
				settype($spec, 'array');
			}
	
			// force 'ASC' or 'DESC' on each order spec, default is ASC.
			foreach ($spec as $key => $val) {
				$asc  = (strtoupper(substr($val, -4)) == ' ASC');
				$desc = (strtoupper(substr($val, -5)) == ' DESC');
				if (! $asc && ! $desc) {
					$val .= ' ASC';
				}
				$this->_parts['order'][] = trim($val);
			}
	
			return $this;
		}
	
	
		/**
		 * Sets a limit count and offset to the query.
		 *
		 * @param int $count The number of rows to return.
		 * @param int $offset Start returning after this many rows.
		 * @return void
		 */
		function limit($count = null, $offset = null) {
			$this->_parts['limitCount']  = (int) $count;
			$this->_parts['limitOffset'] = (int) $offset;
			return $this;
		}
	
	
		/**
		 * Sets the limit and count by page number.
		 *
		 * @param int $page Limit results to this page number.
		 * @param int $rowCount Use this many rows per page.
		 * @return void
		 */
		function limitPage($page, $rowCount) {
			$page = ($page > 0) ? $page : 1;
			$rowCount = ($rowCount > 0) ? $rowCount : 1;
			
			$this->_parts['limitCount']  = (int) $rowCount;
			$this->_parts['limitOffset'] = (int) $rowCount * ($page - 1);
			return $this;
		}
	
		/**
		 * Adds to the internal table-to-column mapping array.
		 *
		 * @param string $tbl The table/join the columns come from.
		 * @param string|array $cols The list of columns; preferably as
		 * an array, but possibly as a comma-separated string.
		 * @return void
		 */
		function _tableCols($tbl, $cols) {
			if (is_string($cols)) {
				$cols = explode(',', $cols);
			} else {
				settype($cols, 'array');
			}
	
			foreach ($cols as $col) {
				$this->_parts['cols'][] = trim($col);
			}
		}
	
	}
	
