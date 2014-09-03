<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once (SHARED_ADDONPATH . 'libraries/MongoQB/src/MongoQB/Builder.php');
ini_set('memory_limit', '128M');

class XervmonMongoQB
{
	private static $mongoQB;
	private static $_ci;
	private static $_config_file = 'mongoqb';
	public static $_customerIdentifier;
	private static $loadConfig = false;
	
	static $config = array(
    'dsn'  =>  'mongodb://xervmon:xervmon2763man@192.241.230.33:27017/xdocker',
    'persist'   =>  true,
    'persist_key'   =>  'mongoqb',
    'replica_set'   =>  false,
    'query_safety'  =>  'safe',
	);
	
	private static function init()
	{
		self::$_ci = ci();
		if (self::$_ci)
		{
			log_message('debug', 'Loading Configuration Data for MongoQB :' . self::$_config_file);
			$config = self::$config;
			
			try
			{
				self::$mongoQB = new \MongoQB\Builder($config);
				self::$_customerIdentifier = trim($config['customer_identifier']);
				self::$loadConfig = true;
				log_message('debug', 'Connected to MongoQB...'. json_encode($config));
			}
			catch(Exception $ex)
			{
				self::$loadConfig = false;
				log_message("error", 'Error while connecting to MongoQB '. json_encode($ex));
				show_error('Error while loading config database! ', 500);
			}
		}
	}
	public function __construct()
	{
		if(!self::$loadConfig) // If not loaded, then load config. So loading only once.
		self::init();
		/*$this->_ci = ci();
		if ($this->_ci)
		{
			log_message('debug', 'Loading Configuration Data for MongoQB :' . $this->_config_file);
			$config = $this->_ci->config->load($this->_config_file);
			
			log_message('debug', 'Configuration...'. json_encode($this->_ci->config->item('mongoqb')));
			try
			{
				$this->mongoQB = new \MongoQB\Builder($this->_ci->config->item('mongoqb'));
				$this->_customerIdentifier = trim($config['customer_identifier']);
				log_message('debug', 'Connected to MongoQB...'. json_encode($config));
			}
			catch(Exception $ex)
			{
				log_message("error", 'Error while connecting to MongoQB '. json_encode($ex));
				show_error('Error while connecting to MongoQB ', 500);
			}
		}*/
	}
	
	public function get($collection = '', $return_cursor = FALSE){
		return self::$mongoQB->get($collection,$return_cursor);
	}
	
	public function getTailable($collection = '', $return_cursor = FALSE){
		return  self::$mongoQB->get($collection,$return_cursor);
	}
	
	public function getAuditLogMessage(){
		$where['customerIdentifier'] =  self::$_customerIdentifier;
		return  self::$mongoQB->getWhere('auditLogMessage',$where);
	}
	
	public function insert($collection = '', $insert = array(), $options = array())
	{
		$insert['customerIdentifier'] = self::$_customerIdentifier;
		return  self::$mongoQB->insert($collection ,$insert, $options);
	}
	/*
	 * 
	 * $mongodb = new Mongo("mongodb://username:password@localhost/database_name");
$database = $mongodb->database_name;
$collection = $database->collection;
 
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 12;
$skip = ($page - 1) * $limit;
$next = ($page + 1);
$prev = ($page - 1);
$sort = array('createdAt' => -1);
 
$cursor = $collection->find()->skip($skip)->limit($limit)->sort($sort);
foreach ($cursor as $r) {
echo sprintf('<p>Added on %s. Last viewed on %s. Viewed %d times. </p>', $r['createdAt'], $r['lastViewed'], $r['counter']);
}
 
if($page > 1){
echo '<a href="?page=' . $prev . '">Previous</a>';
if($page * $limit < $total) {
echo ' <a href="?page=' . $next . '">Next</a>';
}
} else {
if($page * $limit < $total) {
echo ' <a href="?page=' . $next . '">Next</a>';
}
}
 
	 */
	
	public function getData($collection, $select, $where = array(), $limit = 25, $offset = 0)
	{
		$where['customerIdentifier'] = self::$_customerIdentifier;
		//@TODO
		return self::$mongoQB->select($select)->limit($limit)->offset($offset)
		->orderBy(array('_id' => 'desc'))->getWhere($collection, $where);	
		//return $this->_mongo_db->limit($limit)->offset($offset)->order_by(array('_id' => 'desc'))->get_where($collection, $where);	
	}
	
	public static function getCustomerIdentifier()
	{
		if(self::$loadConfig) self::init();
		
		return self::$_customerIdentifier;
	}
	
	public function getMongoQB()
	{
		return self::$mongoQB;
	} 
	public function limit($limit = 99)
	{
		return self::$mongoQB->limit($limit);
	}
	
	public function orderBy($fields = array())
	{
		return self::$mongoQB-> orderBy($fields);
	}
	
	public static function globalInstance() 
    {
    	static $instance = null;
        if($instance === null) {
        	ini_set('memory_limit', '128M');
            $instance = new XervmonMongoQB();
        }
         
        return($instance);
    }
	
}