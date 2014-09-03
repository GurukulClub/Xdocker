<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * PyroCMS Settings Library
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Libraries
 */
class Graphene {

	/**
	 * Settings cache
	 *
	 * @var	array
	 */
	private static $cache = array();
	
	private $graphiteRoot;
	
	private $fromSpan ='-2hours';
	private $toSpan = 'now';
	
	/**
	 * The Graphene Construct - loads the tenant server
	 */
	public function __construct()
	{
	}
	
	public function setGraphiteRoot($data, $urlPattern)
	{
		$stringToBeReplaced = $urlPattern;
		foreach($data as $conn)
		{
			$stringToBeReplaced = str_replace($conn -> slug, $conn -> value, $stringToBeReplaced);
		}
		$this->graphiteRoot = $stringToBeReplaced;
	}
	
	
	public function setGraphiteUrl($theUrl)
	{
		$this->graphiteRoot = $theUrl;
	}
	
	public function getGraphiteUrl()
	{
		return $this->graphiteRoot;
	}
	
	public function setFromToSpan($from, $to)
	{
		$this->fromSpan = $from;
		$this->toSpan = $to;
	}
	
	/*
	 * The url is constructed based on from - now and the format
	 * 
	 */
	public function makeGraphiteUrl($targets, $from="-2hours", $until ='now', $format ='json') 
	{
		$query = "from=$from&until=$until&format=$format";
		foreach ($targets as $t) 
		{
			$query .= "&target=$t";
		}
		return $this->graphiteRoot."/render?".$query;
	}
	
	/**
	 * Plots the memory to cover metrics
	 * 
	 */
	public function plotMemory($path) 
	{
		$targets = array($path."memory-buffered",
							$path."memory-cached",
							$path."memory-free",
							$path."memory-used");
		$name = "Memory";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotLoad($path) 
	{
		$targets = array($path."load.shortterm", $path."load.midterm", $path."load.longterm");
		$name = "Load";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotUsers($path) 
	{
		$targets = array($path."users");
		$name = "Users";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotPing($path) 
	{
		$targets = array($path."ping-localhost", $path."ping_droprate-localhost", $path."ping_stddev-localhost");
		$name = "Ping";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotCpu($path, $targets = array()) 
	{
		if(empty($targets))
		{
			$targets = array(
							$path."cpu-idle",
							$path."cpu-interrupt",
							$path."cpu-nice",
							$path."cpu-softirq",
							$path."cpu-steal", 
							$path."cpu-system", 
							$path."cpu-user",
							$path."cpu-wait"
					);
		}
		$matches = array();
		preg_match("/cpu-(\d+)/", $path, $matches);
		$cpunum = $matches[1] + 1;
		$name = "CPU $cpunum";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotDf($path) 
	{
		$targets = array($path."df_complex-used", $path."df_complex-free", $path."df_complex-reserved");
		$matches = array();
		preg_match("/df-([^.]+)/", $path, $matches);
		$diskname = $matches[1];
		if ($diskname == "root") 
		{
			$diskname = "/";
		}
		$name = "Disk free ($diskname)";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotDisk($path) 
	{
		$targets = array($path."disk_ops.read", $path."disk_ops.write");
		$matches = array();
		preg_match("/disk-([^.]+)/", $path, $matches);
		$diskname = $matches[1];
		$name = "Disk ($diskname)";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotInterface($path) 
	{
		$targets = array($path."if_packets.rx", $path."if_packets.tx", 
						$path."if_octets.rx", $path."if_octets.tx",
						$path."if_errors.rx", $path."if_errors.tx");
		$matches = array();
		preg_match("/interface-([^.]+)/", $path, $matches);
		$interface = $matches[1];
		$name = "Interface $interface";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotMySQLTX($path)
	{
		$targets = array($path."mysql_octets.rx", $path."mysql_octets.tx");
		$matches = array();
		preg_match("/mysql-([^.]+)/", $path, $matches);
		$mysql = $matches[1];
		$name = "MYSQL $mysql";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotMySQLCommands($path)
	{
		$$targets = array($path."mysql_commands-admin_commands",
						$path."mysql_commands-alter_table",
						 $path."mysql_commands-begin",
						 $path."mysql_commands-commit",
						 $path."mysql_commands-create_db",
						 $path."mysql_commands-create_index",
						 $path."mysql_commands-create_table",
						 $path."mysql_commands-dealloc_sql",
						 $path."mysql_commands-delete",
						 $path."mysql_commands-drop_db",
						 $path."mysql_commands-drop_table",
						 $path."mysql_commands-empty_query",
						 $path."mysql_commands-execute_sql",
						 $path."mysql_commands-flush",
						 $path."mysql_commands-grant",
						 $path."mysql_commands-insert",
						 $path."mysql_commands-insert_select",
						 $path."mysql_commands-prepare_sql",
						 $path."mysql_commands-rename_table",
						 $path."mysql_commands-replace",
					     $path."mysql_commands-revoke",
						 $path."mysql_commands-rollback",
						 $path."mysql_commands-select",
						 $path."mysql_commands-set_option",
						 $path."mysql_commands-show_binlogs",
						 $path."mysql_commands-show_create_table",
						 $path."mysql_commands-show_databases",
						 $path."mysql_commands-show_engine_status",
						 $path."mysql_commands-show_fields",
						 $path."mysql_commands-show_function_status",
						 $path."mysql_commands-show_grants",
						 $path."mysql_commands-show_keys",
						 $path."mysql_commands-show_master_status",
						 $path."mysql_commands-show_plugins",
						 $path."mysql_commands-show_procedure_status",
						 $path."mysql_commands-show_processlist",
						 $path."mysql_commands-show_slave_status",
						 $path."mysql_commands-show_status",
						 $path."mysql_commands-show_storage_engines",
						 $path."mysql_commands-show_table_status",
						 $path."mysql_commands-show_table",
						 $path."mysql_commands-show_triggers",
						 $path."mysql_commands-show_variables",
						 $path."mysql_commands-show_warnings",
						 $path."mysql_commands-show_truncate",
						 $path."mysql_commands-show_update",
						);
		$matches = array();
		preg_match("/mysql-([^.]+)/", $path, $matches);
		$mysql = $matches[1];
		$name = "MYSQL $mysql";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}

	public function plotMySQLHandler($path)
	{
		$targets = array($path."mysql_handler-commit", 
						 $path."mysql_handler-delete",
						 $path."mysql_handler_read_first",
						 $path."mysql_handler_read_key",
						 $path."mysql_handler_read_last",
						 $path."mysql_handler_read_next",
						 $path."mysql_handler_read_prev",
						 $path."mysql_handler_read_rnd",
						 $path."mysql_handler_read_rnd_next",
						 $path."mysql_commands-rollback",
						 $path."mysql_commands-update",
						 $path."mysql_commands-write",
						 );
		$matches = array();
		preg_match("/mysql-([^.]+)/", $path, $matches);
		$mysql = $matches[1];
		$name = "MYSQL $mysql";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotMySQLLocks($path)
	{
		$targets = array($path."mysql_locks-immediate", 
						 $path."mysql_locks-waited",
						 
						 );
		$matches = array();
		preg_match("/mysql-([^.]+)/", $path, $matches);
		$mysql = $matches[1];
		$name = "MYSQL $mysql";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotMySQLthreads($path)
	{
		$targets = array($path."threads-cached", 
						 $path."threads-connected",
						 $path."threads-running",
						 $path."total_threads-created",
						 );
		$matches = array();
		preg_match("/mysql-([^.]+)/", $path, $matches);
		$mysql = $matches[1];
		$name = "Mysql $mysql";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotApache($path)
	{
		$targets = array($path."apache_bytes", 
						 $path."apache_connections",
						 $path."apache_idle_workers",
						 $path."apache_requests",
						 $path."apache_scoreboard-closing",
						 $path."apache_scoreboard-dnslookup",
						 $path."apache_scoreboard-finishing",
						 $path."apache_scoreboard-idle_cleanup",
						 $path."apache_scoreboard-keepalive",
						 $path."apache_scoreboard-logging",
						 $path."apache_scoreboard-open",
						 $path."apache_scoreboard-reading",
						 $path."apache_scoreboard-sending",
						 $path."apache_scoreboard-starting",
						 $path."apache_scoreboard-waiting",
						 );
		$matches = array();
		preg_match("/apache-([^.]+)/", $path, $matches);
		
		$apache = $matches[1];
		
		$name = "Apache $apache";
		
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name" => $name, "target" => $url);
	}
	
	public function plotProcesses($path) 
	{
		$targets = array($path."fork_rate", 
						 $path."ps_state-blocked", 
						 $path."ps_state-paging",
						 $path."ps_state-running",
						 $path."ps_state-sleeping",
						 $path."ps_state-stopped",
						 $path."ps_state-zombies"
						 );
		
		$name = "Proceses";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotSwap($path) 
	{
		$targets = array($path."swap-cached", 
						 $path."swap-free", 
						 $path."swap-used",
						 $path."swap_io-in",
						 $path."swap_io-out",
						 );
		
		$name = "Swap";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotUptime($path) 
	{
		$targets = array($path."uptime"
						 );
		
		$name = "Uptime";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=>$name, "target"=>$url);
	}
	
	public function plotNagiosCheckHttp($path)
	{
		$targets = array($path."delay-tinst");
		
		$name = "Nagios-Check HTTP";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $name, "target"=>$url);
	}
	public function plotNagiosCheckLoad($path)
	{
		$targets = array($path."delay-tinst");
	
		$name = "Nagios-Check Load";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $name, "target"=>$url);
	}
	
	public function plotNagiosCheckTCP($path)
	{
		$targets = array($path."delay-tinst");
	
		$name = "Nagios-Check TCP";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $name, "target"=>$url);
	}
	public function plotNagiosCheckUsers($path)
	{
		$targets = array($path."delay-tinst");
	
		$name = "Nagios-Check Users";
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $name, "target"=>$url);
	}
	
	public function plotChartNormal($p)
	{
		$targets = array($p->path);
		$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $p->name, "target" => $url);
	}
	
	public function plotChartNormalX($p)
	{
		$targets = array($p->path);
		//$url = $this->makeGraphiteUrl($targets, $this->fromSpan, $this->toSpan);
		return array("name"=> $p->name);
	}
	
	
	
	
	
	public function getJsonResponse($url, $postdata, $isJson = false) 
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		$response = curl_exec( $ch );
		if($isJson)
			return $response;
		else return json_decode($response);
	}
	
	public function doGraphiteFind($query) 
	{
		$url = $this->graphiteRoot . "/metrics/find/";
		$data = array("query" => $query,
				"format" => "completer");
		return $this->getJsonResponse($url, $data);
	}
	
	public function getClients() 
	{
		$users = $this->doGraphiteFind("");
		$ret = array();
		foreach ($users->metrics as $u) 
		{
			array_push($ret, $u->name);
		}
		return $ret;
	}
	
	public function getServers($client) 
	{
		$servers = $this->doGraphiteFind("$user.");
		$ret = array();
		if(!empty($servers)) 
		{
			foreach ($servers->metrics as $u) 
			{
				array_push($ret, $u->name);
			}
		}
		return $ret;
	}
	
	public function getPlugins($client, $server) 
	{
		$plugins = $this->doGraphiteFind("$client.$server.");
		return $plugins;
	}
	
	public function startswith($str, $startswith) 
	{
		return strpos($str, $startswith) === 0;
	}

	
}

/* End of file Graphene.php */

