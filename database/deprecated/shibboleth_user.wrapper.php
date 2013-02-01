<?php

#don't use

class ShibbolethUserMapper extends Mapper {
	
	private $tableName;
	
	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct($api){
		parent::__construct($api);
		$this->tableName = '*PREFIX*shibboleth_user';
	}
	
	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function find($id){
		$row = $this->findQuery($this->tableName, $id);
		return new Item($row);
	}
	
}
