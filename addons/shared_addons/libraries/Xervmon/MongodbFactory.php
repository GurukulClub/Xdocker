<?php
defined('BASE') or exit('Direct script access is not allowed!');

abstract class MongoDBFactory
{
    // Singleton pattern.
    static $dbh;
	static $config = array(
    'dsn'  =>  'mongodb://xervmon:xervmon2763man@192.241.230.33:27017/xdocker',
    'persist'   =>  true,
    'persist_key'   =>  'mongoqb',
    'replica_set'   =>  false,
    'query_safety'  =>  'safe',
	);
    /**
     * @return Database Handler
     */
    public static function getDBHandler()
    {
        if ( !isset(self::$dbh) )
        {
            global $mongodb_i;
			$mongodb_i = self::$config;
            $dbname = $mongodb_i['dbname'];
            $ret = null;
            try {
                $replicaSet = trim($mongodb_i['replicaSet']);
                if (strlen($replicaSet) > 0) {
                    $ret = new Mongo('mongodb://'.$mongodb_i['host'], array("replicaSet" => $replicaSet) );
                } else {
                    $ret = new Mongo('mongodb://'.$mongodb_i['host'] );
                }
                $ret = $ret->$dbname; // select a database
            }
            catch(Exception $e)
            {
                die('MongoDBFactory: '.$e->getMessage());
            }
            self::$dbh = $ret;
            return self::$dbh;
        }
        return self::$dbh;
    }
}
