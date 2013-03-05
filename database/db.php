<?php
namespace OCA\user_shibboleth;

class DB {

	public static function loginNameExists($loginName) {
		$query = \OCP\DB::prepare('SELECT COUNT(*) FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $query->execute(array($loginName));
		
		if (!\OCP\DB::isError($result)) {
			$count = $result->fetchAll(\PDO::FETCH_COLUMN, 0); 
			return $count[0] === 1;
		}
		return false;
	}
	
	
	public static function getHomeDir($loginName) {
		$query = \OCP\DB::prepare('SELECT home_dir FROM *PREFIX*shibboleth_user WHERE login_name = ?');
                $result = $query->execute(array($loginName));

                if (!\OCP\DB::isError($result)) {
                        $homeDirectories = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
                        if (count($homeDirectories) === 1)
                                return $homeDirectories[0];
                }
                return false;
	}
	
	
	public static function getLoginNames($partialLoginName, $limit, $offset) {//was getUsers
		//prepare parameters for use in prepared statement
		$partialLoginName = '%'.$partialLoginName.'%';
		$limit = strval($limit);//might be 0, cast to string
		$offset = strval($offset);//dito
		
		//run query 
		$query = \OCP\DB::prepare('SELECT login_name FROM *PREFIX*shibboleth_user WHERE login_name LIKE ? LIMIT ? OFFSET ?');
		$result = $query->execute(array( $partialLoginName, $limit, $offset));

		if (\OCP\DB::isError($result)) {
			return false;
		} else {
			return $result->fetchAll(\PDO::FETCH_COLUMN, 0);
		}
	}
	
	
	public static function loginOrDisplayNameExists($name) {
		$query = \OCP\DB::prepare('SELECT COUNT(*) FROM *PREFIX*shibboleth_user WHERE login_name = ? OR display_name = ?');
		$result = $query->execute(array($name, $name));
	
		if (!\OCP\DB::isError($result)) {
			$count = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
			return $count[0] === 1;
                }
                return false;
	}
	
	public static function getDisplayName($loginName) {
		$query = \OCP\DB::prepare('SELECT display_name FROM *PREFIX*shibboleth_user WHERE login_name = ?');
		$result = $query->execute(array($loginName));
		
		if (!\OCP\DB::isError($result)) {
			$displayNames = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
			if (count($displayNames) === 1)
				return $displayNames[0];
		}
		return false;
	}
	
	public static function getDisplayNames($partialDisplayName, $limit, $offset) {
		//prepare parameters for use in prepared statement
		$partialDisplayName = '%'.$partialDisplayName.'%';
		$limit = strval($limit);//might be 0, cast to string
		$offset = strval($offset);//ditto
		
		//run query
		$query = \OCP\DB::prepare('SELECT login_name, display_name FROM *PREFIX*shibboleth_user WHERE display_name LIKE ? LIMIT ? OFFSET ?');
		$result = $query->execute(array($partialDisplayName, $limit, $offset));
		
		if (!\OCP\DB::isError($result)) {
			$rows = $result->fetchAll(\PDO::FETCH_ASSOC);
			$array = array();
			foreach ($rows as $row) {
				$loginName = $row['login_name'];
				$displayName = $row['display_name'];
				$array[$loginName] = $displayName;
			}
			return $array;
		}
		return false;
	}
	
	public static function addUser($loginName, $displayName, $homeDir) {
		$query = \OCP\DB::prepare('INSERT INTO *PREFIX*shibboleth_user values(?, ?, ?)');
		$result = $query->execute(array($loginName, $displayName, $homeDir));
                if (\OCP\DB::isError($result))//does not prevent errors!
			return false;
		return true;
	}

	public static function updateDisplayName($loginName, $displayName) {
		$query = \OCP\DB::prepare('UPDATE *PREFIX*shibboleth_user SET display_name = ? WHERE login_name = ?');
		$result = $query->execute(array($displayName, $loginName));
		if (\OCP\DB::isError($result))
			return false;
		return true;
	}

	public static function deleteUser($loginName) {
		$query = \OCP\DB::prepare('DELETE FROM *PREFIX*shibboleth_user WHERE login_name = ?');
                $result = $query->execute(array($loginName));
                if (\OCP\DB::isError($result))
                        return false;
                return true;
	}

}
