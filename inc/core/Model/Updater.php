<?php
/**
 * This file contains class::Updater
 * @package Runalyze\Model
 */

namespace Runalyze\Model;

/**
 * Update object in database
 * 
 * @author Hannes Christiansen
 * @package Runalyze\Model
 */
abstract class Updater {
	/**
	 * PDO
	 * @var \PDO
	 */
	protected $PDO;

	/**
	 * Old object
	 * @var \Runalyze\Model\Object
	 */
	protected $OldObject;

	/**
	 * New object
	 * @var \Runalyze\Model\Object
	 */
	protected $NewObject;

	/**
	 * Construct updater
	 * @param \PDO $connection
	 * @param \Runalyze\Model\Object $newObject [optional]
	 * @param \Runalyze\Model\Object $oldObject [optional]
	 */
	public function __construct(\PDO $connection, Object $newObject = null, Object $oldObject = null) {
		$this->PDO = $connection;
		$this->NewObject = $newObject;
		$this->OldObject = $oldObject;
	}

	/**
	 * Tablename without prefix
	 * @return string
	 */
	abstract protected function table();

	/**
	 * Where clause
	 * @return string
	 */
	abstract protected function where();

	/**
	 * Keys to update
	 * @return array
	 */
	protected function keys() {
		if (!is_null($this->NewObject)) {
			return $this->NewObject->properties();
		}

		return array();
	}

	/**
	 * Ignore specific key
	 * 
	 * This method may be overwritten in the subclass to ignore some specific keys,
	 * e.g. don't update `A` if `B` has a specific value.
	 * @param type $key
	 * @return boolean
	 */
	protected function ignore($key) {
		return false;
	}

	/**
	 * Update
	 * @param \Runalyze\Model\Object $object [optional]
	 * @param array $keys [optional]
	 * @throws \RuntimeException
	 */
	final public function update(Object $object = null, $keys = false) {
		if (!is_null($object)) {
			$this->NewObject = $object;
		}

		if (is_null($this->NewObject)) {
			throw new \RuntimeException('The updater does not have any object to update.');
		}

		if ($keys === false) {
			$keys = $this->keys();
		}

		$this->before();
		$this->runUpdate($keys);
		$this->after();
	}

	/**
	 * Run update
	 * @param array $keys
	 */
	private function runUpdate($keys) {
		$set = array();

		foreach ($keys as $key) {
			if (!$this->ignore($key) && $this->hasChanged($key)) {
				$value = $this->value($key);
				$quoted = is_null($value) ? 'NULL' : $this->PDO->quote($value);
				$set[] = '`'.$key.'` = '.$quoted;
			}
		}

		if (!empty($set)) {
			$this->PDO->exec(
				'UPDATE `'.PREFIX.$this->table().'`
				SET '.implode(', ', $set).'
				WHERE '.$this->where()
			);
		}
	}

	/**
	 * Knows old object
	 * @return boolean
	 */
	protected function knowsOldObject() {
		return (null !== $this->OldObject);
	}

	/**
	 * Has the value changed?
	 * @param string $key
	 * @return boolean
	 */
	protected function hasChanged($key) {
		if ($this->knowsOldObject()) {
			return ($this->OldObject->get($key) != $this->NewObject->get($key));
		}

		return true;
	}

	/**
	 * Value for key
	 * @param string $key
	 * @return string
	 */
	protected function value($key) {
		$value = $this->NewObject->get($key);

		if (is_array($value)) {
			return Object::implode($value);
		}

		return $value;
	}

	/**
	 * Tasks before update
	 */
	protected function before() {
		$this->NewObject->synchronize();
	}

	/**
	 * Tasks after update
	 */
	protected function after() {}
}