<?php


/*
 *
 * The following SQL statement is just a help for developers and will not be
 * executed!
 *
 * CREATE TABLE  `oc_group_preferences` (
 * `gid` VARCHAR( 255 ) NOT NULL ,
 * `configkey` VARCHAR( 255 ) NOT NULL ,
 * `configvalue` VARCHAR( 255 )
 * )
 *
 */


/**
 * Jawinton
 */
class OC_GroupPreferences{

	public static function getValue( $gid, $key = 'status', $default = null ) {
		// At least some magic in here :-)
		$query = OC_DB::prepare( 'SELECT `configvalue` FROM `*PREFIX*group_preferences`'
			.' WHERE `gid` = ? AND `configkey` = ?' );
		$result = $query->execute( array( $gid, $key ));
		$row = $result->fetchRow();
		if($row) {
			return $row["configvalue"];
		}else{
			return $default;
		}
	}

	public static function setValue( $gid, $value, $key = 'status' ) {
		// Does the key exist? yes: update. No: insert
		if(! self::hasKey($gid, $key)) {
			$query = OC_DB::prepare( 'INSERT INTO `*PREFIX*group_preferences` ( `gid`, `configkey`, `configvalue` )'
				.' VALUES( ?, ?, ? )' );
			$query->execute( array( $gid, $key, $value ));
		}
		else{
			$query = OC_DB::prepare( 'UPDATE `*PREFIX*group_preferences` SET `configvalue` = ?'
				.' WHERE `gid` = ? AND `configkey` = ?' );
			$query->execute( array( $value, $gid, $key ));
		}
		return true;
	}

	public static function hasKey($gid, $key) {
		$exists = self::getKeys( $gid );
		return in_array( $key, $exists );
	}

	public static function getKeys( $gid ) {
		// No magic in here as well
		$query = OC_DB::prepare( 'SELECT `configkey` FROM `*PREFIX*group_preferences` WHERE `gid` = ?' );
		$result = $query->execute( array( $gid ));

		$keys = array();
		while( $row = $result->fetchRow()) {
			$keys[] = $row["configkey"];
		}

		return $keys;
	}

}
