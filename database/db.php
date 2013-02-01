<?php
namespace OCA\user_shibboleth;

class DB {
	public static function getUsers($search, $limit, $offset) {
		//prepare parameters for use in prepared statement
		$search = '%'.$search.'%';
		$limit = strval($limit);//might be 0, cast them to string
		$offset = strval($offset);//dito
		
		//run query 
		$query = \OCP\DB::prepare('SELECT owncloud_name FROM *PREFIX*shibboleth_user WHERE owncloud_name LIKE ? LIMIT ? OFFSET ?');
		$result = $query->execute(array($search, $limit, $offset));

		if (\OCP\DB::isError($result)) {
			return false;
		} else {
			return $result->fetchAll(\PDO::FETCH_COLUMN, 0);
		}
	}

	public static function userExists($search) {
		$query = \OCP\DB::prepare('SELECT owncloud_name FROM *PREFIX*shibboleth_user WHERE owncloud_name = ?');
                $result = $query->execute(array($search));

                if (!\OCP\DB::isError($result)) {
                        $names = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
			if (count($names) === 1)
				return true;
                }
		return false;
	}
}
