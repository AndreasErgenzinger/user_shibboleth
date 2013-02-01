<?php

#don't use

class ShibbolethUser {
	
	private $owncloudName;
	
	public function __construct($fromRow=null) {
		if($fromRow){
			$this->fromRow($fromRow);
		}
	}
	
	public function fromRow($row) {
		$this->owncloudName = $row['owncloudName'];
	}
	
	public function getOwncloudName() {
		return $this->owncloudName;
	}
	
	public function setOwncloudName($owncloudName) {
		$this->owncloudName = $owncloudName;
	}
}
