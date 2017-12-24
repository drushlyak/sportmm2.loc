<?php

require_once (MAIN_LIB_PATH . "/MilKit/Exception.php");

class MilKit_EventManager {
	private $events = array();

	private $eventsSuspended = false;

	/**
	 * Добавить объекту события
	 *
	 * @param array $a
	 */
	public function addEvents ($a) {
		foreach ($a as $eventName) {
			$eventName = strtolower($eventName);
			if (!array_key_exists($eventName, $this->events)) {
				$this->events[$eventName] = array();
			}
		}
	}

	/**
	 * Зажигает событие $eventName с переданными параметрами
	 *
	 * @param string $eventName
	 * @return bool
	 */
	public function fire ($eventName /** args */) {
		if (!$this->eventsSuspended) {
			$listeners = $this->events[strtolower($eventName)];
			if ($listeners) {
				$args = array_slice(func_get_args(), 1);
				foreach ($listeners as $callback) {
					$r = call_user_func_array($callback, $args);
					if ($r === false) {
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 * Добавить слушателя объекта
	 *
	 * @param string $eventName
	 * @param string/array $callback
	 */
	public function addListener ($eventName, $callback)	{
		$eventName = strtolower($eventName);

		if (!array_key_exists($eventName, $this->events)) {
			throw new MilKit_Exception("Событие '$eventName' не определено");
		}

		if (!is_callable($callback)) {
			throw new MilKit_Exception("Слушатель не является функцией");
		}

		$this->events[$eventName][] = $callback;

		return true;
	}

	/**
	 * Удалить слушателя объекта
	 *
	 * @param string $eventName
	 * @param string/array $callback
	 */
	public function removeListener ($eventName, $callback) {
		$eventName = strtolower($eventName);

		if (!array_key_exists($eventName, $this->events)) {
			throw new MilKit_Exception("Событие '$eventName' не определено");
		}

		if (false !== ($k = array_search($callback, $this->events[$eventName]))) {
			unset($this->events[$eventName][$k]);
			return true;
		}

		return false;
	}

	public function removeAllListeners () {
		foreach (array_keys($this->events) as $eventName) {
			$this->events[$eventName] = array();
		}
	}

	/**
	 * Возобновить передачу событий
	 */
	public function resumeEvents () {
		$this->eventsSuspended = false;
	}

	/**
	 * Приостановить передачу событий
	 */
	public function suspendEvents () {
		$this->eventsSuspended = true;
	}

	/**
	 * Alias for addListener
	 *
	 * @see addListener
	 */
	public function on ($eventName, $callback) {
		$this->addListener($eventName, $callback);
	}

	/**
	 * Alias for removeListener
	 */
	public function un ($eventName, $callback) {
		$this->removeListener($eventName, $callback);
	}
}
