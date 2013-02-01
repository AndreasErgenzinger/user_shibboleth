<?php

namespace OCA\user_shibboleth;

class Utils {

	public static function endsWith($string, $suffix, $caseInsensitive = true) {
		$stringLength = strlen($string);
		$suffixLength = strlen($suffix);
		if ($suffixLength < $stringLength) {
			$comp = substr_compare($string, $suffix, $stringLength - $suffixLength, $suffixLength, $caseInsensitive);
			if ($comp === 0)
				return true;
		}
		return false;
	}

}
