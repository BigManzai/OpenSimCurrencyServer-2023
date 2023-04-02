<?PHP
// 
//  Copyright (c)Melanie Thielker and Teravus Ovares (http://opensimulator.org/)
// 
//  Redistribution and use in source and binary forms, with or without
//  modification, are permitted provided that the following conditions are met:
//      * Redistributions of source code must retain the above copyright
//        notice, this list of conditions and the following disclaimer.
//      * Redistributions in binary form must reproduce the above copyright
//        notice, this list of conditions and the following disclaimer in the
//        documentation and/or other materials provided with the distribution.
//      * Neither the name of the OpenSim Project nor the
//        names of its contributors may be used to endorse or promote products
//        derived from this software without specific prior written permission.
// 
//  THIS SOFTWARE IS PROVIDED BY THE DEVELOPERS ``AS IS'' AND ANY
//  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
//  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
//  DISCLAIMED. IN NO EVENT SHALL THE CONTRIBUTORS BE LIABLE FOR ANY
//  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
//  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
//  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
//  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
//  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
//  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

// This file enables buying currency in the client.
//
// For this to work, the all clients using currency need to add
//
//                -helperURI <WebpathToThisDirectory>
//
// to the commandline parameters when starting the client!
//
// Example:
//    OpenSim-Viewer.exe -loginuri http://yourgrid.com:8002/ -helperuri http://yourweb.com/helper/
//
// Don't forget to change the currency conversion value in the wi_economy_money
// table!
//
// This requires PHP curl, XMLRPC, and MySQL extensions.
//
// If placed in the opensimwiredux web directory, it will share the db module

error_reporting(E_ALL  & ~E_NOTICE);

function xlog($text) 
  {
  global $IP;
  $datum   = date("Y-m-d H:i:s");
  $fh = fopen('currency.log', 'a'); // bitte anpassen
  fwrite($fh, $datum."# ".$IP."|".$text."\n");
  fclose($fh);
  }
xlog(".. ");    
$request_xml = file_get_contents("php://input");
xlog("Call... request_xml -  ".$request_xml);

  # Splt Post raw Content as an effect because somethin does not more run under php 7.x
  # Split Post Raw Content als Effekt, weil etwas nicht mehr unter PHP 7.x läuft
  $search  = array('<', '>', 'methodCall', '?xml version="1.0"?', '/', 'xxx', 'methodName', 'membername' , 'params' , 'param' , 'value' , 'struct', 'member', 'name', 'string', 'int'); 
  $xml = str_replace($search, ">" ,$request_xml);
  $search  = array( '>>'); 
  $xml = str_replace($search, ">" ,$xml);
  $xml = str_replace($search, ">" ,$xml);
  $xml = str_replace($search, ">" ,$xml);
  $xml = str_replace($search, ">" ,$xml);
  $xml = str_replace($search, ">" ,$xml);
  $xml = str_replace($search, ">" ,$xml);
  $xml_split = preg_split("[>]", $xml); 
  

    $method_name = $xml_split[1]; 		// getCurrencyQuote oder buyCurrency
    $agentId = $xml_split[3]; 			// agentId getCurrencyQuote und buyCurrency
    $secureSessionId = $xml_split[5]; 	// secureSessionId getCurrencyQuote und buyCurrency
	$currencyBuy = $xml_split[7]; 		// kauf Betrag  getCurrencyQuote und buyCurrency
	
    $billableArea = $xml_split[9]; 		// estimatedCost = 0  buyCurrency
	$estimatedCost = $xml_split[9]; 	// estimatedCost = 0  buyCurrency
    $amount = $xml_split[11]; 			// ? confirm 123456789  buyCurrency
	$confirm = $xml_split[11]; 			// confirm MoneyServer = 123456789  buyCurrency


    xlog("Call... xml_split 0 ".$xml_split[0]);
    xlog("Call... xml_split 1 ".$xml_split[1]);
    xlog("Call... xml_split 2 ".$xml_split[2]);
    xlog("Call... xml_split 3 ".$xml_split[3]);
    xlog("Call... xml_split 4 ".$xml_split[4]);
    xlog("Call... xml_split 5 ".$xml_split[5]);
    xlog("Call... xml_split 6 ".$xml_split[6]);
    xlog("Call... xml_split 7 ".$xml_split[7]);
    xlog("Call... xml_split 8 ".$xml_split[8]);
    xlog("Call... xml_split 9 ".$xml_split[9]);
    xlog("Call... xml_split 10 ".$xml_split[10]);
    xlog("Call... xml_split 11 ".$xml_split[11]);
	xlog("Call... xml_split 12 ".$xml_split[12]);

	
/* 	Ergebnis:
2020-08-16 12:31:02# |Call... xml_split 0 
2020-08-16 12:31:02# |Call... xml_split 1 getCurrencyQuote
2020-08-16 12:31:02# |Call... xml_split 2 agentId
2020-08-16 12:31:02# |Call... xml_split 3 44504b8b-2c05-4069-9050-319a528aee4f
2020-08-16 12:31:02# |Call... xml_split 4 secureSessionId
2020-08-16 12:31:02# |Call... xml_split 5 6ab09ffd-91eb-490b-854c-723e063f0e85
2020-08-16 12:31:02# |Call... xml_split 6 currencyBuy
2020-08-16 12:31:02# |Call... xml_split 7 1000
2020-08-16 12:31:02# |Call... xml_split 8 

2020-08-16 12:31:06# |Call... xml_split 0 
2020-08-16 12:31:06# |Call... xml_split 1 buyCurrency
2020-08-16 12:31:06# |Call... xml_split 2 agentId
2020-08-16 12:31:06# |Call... xml_split 3 44504b8b-2c05-4069-9050-319a528aee4f
2020-08-16 12:31:06# |Call... xml_split 4 secureSessionId
2020-08-16 12:31:06# |Call... xml_split 5 6ab09ffd-91eb-490b-854c-723e063f0e85
2020-08-16 12:31:06# |Call... xml_split 6 currencyBuy
2020-08-16 12:31:06# |Call... xml_split 7 1000
2020-08-16 12:31:06# |Call... xml_split 8 estimatedCost
2020-08-16 12:31:06# |Call... xml_split 9 0
2020-08-16 12:31:06# |Call... xml_split 10 confirm
2020-08-16 12:31:06# |Call... xml_split 11 123456789
2020-08-16 12:31:06# |Call... xml_split 12 
*/

// Settings //
$dbtype = "mysql";
$dbhost = "localhost";

$dbuser = "username";
$dbpass = "userpswd";
$dbname = "databasename"; // Grid Datenbank
$dbmoneyname = "moneydatabase"; // Money Datenbank
define("SYSURL","http://website.com");

// Tables OpenSim 0.9.2.645 //
$tbname = "regions"; 	// from robust database - regions - uuid, regionHandle, regionName, regionRecvKey, regionSendKey, regionSecret, regionDataURI, serverIP, serverPort, 
						// serverURI, locX, locY, locZ, eastOverrideHandle, westOverrideHandle, southOverrideHandle, northOverrideHandle, regionAssetURI,
						// regionAssetRecvKey, regionAssetSendKey, regionUserURI, regionUserRecvKey, regionUserSendKey, regionMapTexture, serverHttpPort,
						// serverRemotingPort, owner_uuid, originUUID, access, ScopeID, sizeX, sizeY, flags, last_seen, PrincipalID, Token, parcelMapTexture
						
						
$presence = "Presence"; // from robust database - Presence - UserID, RegionID, SessionID, SecureSessionID
$balances = "balances"; // from money database -  balances - user, balance, status, type

// Database (old), todo ...
define("C_DB_TYPE", $dbtype);
define("C_DB_HOST", $dbhost);
define("C_DB_USER", $dbuser);
define("C_DB_PASS", $dbpass);
define("C_DB_NAME", $dbname);
define("C_DB_NAME_MONEY", $dbmoneyname);
define("C_TB_REGIONS", $tbname);


// Key of the account that all fees go to: - Schlüssel des Kontos, auf das alle Gebühren gehen:
$economy_sink_account="00000000-0000-0000-0000-000000000000";
// Key of the account that all purchased currency is debited from: - Das Konto, von dem alle gekauften Währungen abgebucht werden:
$economy_source_account="00000000-0000-0000-0000-000000000000";
// Minimum amount of real currency (in CENTS!) to allow purchasing: - Mindestbetrag der realen Währung (in CENTS!), Um den Kauf zu ermöglichen:
$minimum_real=0;
// Error message if the amount is not reached:
$low_amount_error="You tried to buy less than the minimum amount of currency. You cannot buy currency for less than US$ %.2f.";
//$low_amount_error="Sie haben versucht, weniger als den Mindestbetrag an Währung zu kaufen. Sie können keine Währung für weniger als US$ %.2f. kaufen";
//$low_amount_error="Vous avez essayé d'acheter moins que le montant minimum de devises. Vous ne pouvez pas acheter de devises pour moins de US$ %.2f.";

class DB
{
	var $MoneyDatabase 	= C_DB_NAME_MONEY;  		// Logical database name on that server
	
	var $Host     = C_DB_HOST;  // Hostname of our MySQL server
	var $Database = C_DB_NAME;  // Logical database name on that server
	var $User     = C_DB_USER;  // Database user
	var $Password = C_DB_PASS;  // Database user's password
	var $Link_ID  = 0;          // Result of mysql_connect()
	var $Query_ID = 0;          // Result of most recent mysql_query()
	var $Record   = array();    // Current mysql_fetch_array()-result
	var $Row;                   // Current row number
	var $Errno    = 0;          // Error state of query
	var $Error    = "";         // Error empty to start

/* 	function halt($msg)
	{
		echo("</td></tr></table><strong>Database error:</strong> $msg<br />\n");
		echo("<strong>MySQL error</strong>: $this->Errno ($this->Error)<br />\n");
		die("Session halted.");
	} */
    function halt($msg)
    {
		xlog("function halt: ") . $msg;
        echo "<strong>DB ERROR   :</strong> $msg<br />\n";
        echo "<strong>MySQL ERROR:</strong> $this->Error ($this->Errno)<br />\n";
        die('Session Halted.');
    }

	function connect()
	{
		xlog("function connect"); 
		//if($this->Link_ID == 0)
		if ($this->Link_ID==null)
		{
			$this->Link_ID = mysqli_connect($this->Host, $this->User, $this->Password, $this->Database);
			mysqli_set_charset($this->Link_ID, 'utf8');
		}
	}
	
	function connectmoney()
	{
		xlog("function connect money"); 
		//if($this->Link_ID == 0)
		if ($this->Link_ID==null)
		{
			$this->Link_ID = mysqli_connect($this->Host, $this->User, $this->Password, $this->MoneyDatabase);
			mysqli_set_charset($this->Link_ID, 'utf8');
		}
	}

/*  	function escape($String)
 	{
 		//return mysqli_real_escape_string($this->Link_ID, $String);
		$Output = $String;
		return $Output;
 	} */
     function escape($String)
     {
		 xlog("function escape: " . $String);
         $this->connect();
         return mysqli_real_escape_string($this->Link_ID, $String);
     }

/* 	function query($Query_String)
	{
		$this->connect();
		$this->Query_ID = mysqli_query($this->Link_ID, $Query_String);
		$this->Row = 0;
		//$this->Errno = mysqli_connect_errno();
		//$this->Error = mysqli_connect_errno();

		if (!$this->Query_ID)
		{
			$this->halt("Invalid SQL: ".$Query_String);
		}
		return $this->Query_ID;
	} */
    function query($Query_String)
    {
		xlog("function query: " . $Query_String);
        $this->connect();
        if ($this->Errno!=0) return 0;
		$this->Query_ID = mysqli_query($this->Link_ID, $Query_String);
		$this->Errno = mysqli_errno($this->Link_ID);
		$this->Error = mysqli_error($this->Link_ID);
        $this->Row = 0;
        //
        if (!$this->Query_ID) 
		{
            $this->halt('Invalid SQL: '.$Query_String);
        }
        return $this->Query_ID;
    }

/* 	function next_record()
	{
		$this->Record = @mysqli_fetch_array($this->Query_ID);
		$this->Row += 1;
		//$this->Errno = mysqli_connect_errno();
		//$this->Error = mysqli_connect_errno();
		$stat = is_array($this->Record);

		if (!$stat)
		{
			@mysqli_free_result($this->Query_ID);
			$this->Query_ID = 0;
		}
		return $this->Record;
	} */
    function next_record()
    {
		xlog("function next_record");
		$this->Record = @mysqli_fetch_array($this->Query_ID);
		$this->Row += 1;
		$this->Errno = mysqli_errno($this->Link_ID);
		$this->Error = mysqli_error($this->Link_ID);
		$stat = is_array($this->Record);
		if (!$stat) 
		{
			@mysqli_free_result($this->Query_ID);
			$this->Query_ID = null;
		}

        return $this->Record;
    }

    function insert_record($table, $params)
    {
		xlog("function insert_record: " . $table . $params);
        if (!is_array($params)) return false;
    
        $num  = 0;
        $keys = '';
        $vals = '';
        foreach ($params as $key => $value) {
            if ($num==0) {
                $keys = $key;
                $vals = "'".$value."'";
            }
            else {
                $keys .= ','.$key;
                $vals .= ",'".$value."'";
            }
            $num++;
        }
        
        $this->query('INSERT INTO '.$table.' ('.$keys.') VALUES ('.$vals.')');

        if ($this->Errno==0) return true;
        return false;
    }
	
    function update_record($table, $params)
    {
		xlog("function update_record: " . $table . $params);
        if (!is_array($params)) return false;

        $num = 0;
        $where  = '';
        $setval = '';
        foreach ($params as $key => $value) {
            if ($num==0) {
                $where = $key."='".$value."'";
            }
            else if ($num==1) {
                $setval = $key."='".$value."'";
            }
            else {
                $setval .= ','.$key."='".$value."'";
            }
            $num++;
        }

        $this->query('UPDATE '.$table.' SET '.$setval.' WHERE '.$where);

        if ($this->Errno==0) return true;
        return false;
    }

	function num_rows()
	{
		xlog("function num_rows");
		return mysqli_num_rows($this->Query_ID);
	}


	function affected_rows()
	{
		xlog("function affected_rows");
		return mysqli_affected_rows($this->Link_ID);
	}

/* 	function optimize($tbl_name)
	{
		$this->connect();
		$this->Query_ID = @mysqli_query($this->Link_ID, "OPTIMIZE TABLE $tbl_name");
	} */
    function optimize($tbl_name)
    {
		xlog("function optimize: " . $tbl_name);
        $this->connect();
        if ($this->Errno!=0) return;
		$this->Query_ID = @mysqli_query($this->Link_ID, 'OPTIMIZE TABLE '.$tbl_name);
    }

/* 	function clean_results()
	{
		if($this->Query_ID != 0) mysqli_free_result($this->Query_ID);
	} */
    function clean_results()
    {
		xlog("function clean_results");
        if ($this->Query_ID!=null) 
		{ 
			mysqli_free_result($this->Query_ID);
		}
		$this->Query_ID = null;

    }

	function close()
	{
		xlog("function close database");
		//if($this->Link_ID != 0) mysqli_close($this->Link_ID);
		
		// if($this->Link_ID != 0) 
		// {
			// mysqli_close($this->Link_ID);
		// }
	}


    function exist_table($table, $lower_case=true)
    {
		xlog("function exist_table: " . $table . $lower_case);
        $ret = false;

        if ($lower_case) $table = strtolower($table);

        $this->query('SHOW TABLES');
        if ($this->Errno==0) {
            while (list($db_tbl) = $this->next_record()) {
                if ($lower_case) $db_tbl = strtolower($db_tbl);
                if ($db_tbl==$table) {
                    $ret = true;
                    break;
                }
            }
        }

        return $ret;
    }


    function exist_field($table, $field, $lower_case=true)
    {
		xlog("function exist_field: " . $table . $field . $lower_case);
        $ret1 = false;
        $ret2 = false;

        if ($lower_case) $cmp_table = strtolower($table);
        else             $cmp_table = $table;

        $this->query('SHOW TABLES');
        if ($this->Errno==0) {
            while (list($db_tbl) = $this->next_record()) {
                if ($lower_case) $db_tbl = strtolower($db_tbl);
                if ($db_tbl==$cmp_table) {
                    $ret1 = true;
                    break;
                }
            }
        }

        if ($ret1) {
            $this->query('SHOW COLUMNS FROM '.$table);
            if ($this->Errno==0) {
                while (list($db_fld) = $this->next_record()) {
                    if ($db_fld==$field) {
                        $ret2 = true;
                        break;
                    }
                }
            }
        }

        return $ret2;
    }

    
    //
    // Update_time will be NULL in InnoDB!
    // 
    function get_update_time($table, $unixtime=true)
    {
		xlog("function get_update_time: " . $table . $unixtime);
        $update = '';
        if ($unixtime) $update = 0;

        $this->query("SHOW TABLE STATUS WHERE name='$table'");

        if ($this->Errno==0) {
            $table_status = $this->next_record();
            $update = $table_status['Update_time'];
            if ($unixtime) {
                if ($update!='') $update = strtotime($update);
                else $update = 0;
            }
        }

        return $update;
    } 


    //
    // Lock
    //
    function lock_table($table, $mode='write')
    {
		xlog("function lock_table: " . $table . $mode);
        $this->query("LOCK TABLES ".$table." ".$mode);
    }


    function unlock_table()
    {
		xlog("function unlock_table");
        $this->query("UNLOCK TABLES");
    }


    //
    // Timeout
    //
    function set_default_timeout($tm)
    {
		xlog("function set_default_timeout: " . $tm);
        ini_set('mysql.connect_timeout', $tm);
        $this->Timeout = $tm;
    }


    function set_temp_timeout($tm)
    {
		xlog("function set_temp_timeout: " . $tm);
        ini_set('mysql.connect_timeout', $tm);
    }


    function reset_timeout()
    {
		xlog("function reset_timeout");
        ini_set('mysql.connect_timeout', $this->Timeout);
    }

}

// ### Database functions end

//
// User provided interface routine to interface with payment processor
//
function process_transaction($avatarId, $amount, $ipAddress)
{
	xlog("process_transaction: " . $avatarId . $amount . $ipAddress);
	// Do Credit Card Processing here!  Return False if it fails!
	// Remember, $amount is stored without decimal places, however it's assumed
	// that the transaction amount is in Cents and has two decimal places
	// 5 dollars will be 500
	// 15 dollars will be 1500

	return True;
}

//
// Helper routines
//

function convert_to_real($currency)
{
	xlog("convert_to_real");
	return 0;
}

function update_simulator_balance($agentId)
{
	xlog("update_simulator_balance: " . $agentId);
	$db = new DB;
	$sql = "select serverIP, serverHttpPort from Presence " . "inner join regions on regions.uuid = Presence.RegionID " . "where Presence.UserID = '".$db->escape($agentId)."'";

	// update_simulator_balance($agentid, $sbalance, $secureid);
	// update_simulator_balance($destid,  $dbalance);
	
	// Requested method [balanceUpdateRequest] not found

	$db->query($sql);
	$results = $db->next_record();
	if ($results)
	{
		$serverIp = $results["serverIP"];
		$httpport = $results["serverHttpPort"];
	

		$req      = array('agentId'=>$agentId);
		$params   = array($req);

		//$request  = xmlrpc_encode_request('balanceUpdateRequest', $params);
		$request  = xmlrpc_encode_request('UpdateBalance', $params);
		$response = do_call($serverIp, $httpport, $request); 
	}
	xlog("update_simulator_balance serverIp: " . $serverIp);
	xlog("update_simulator_balance httpport: " . $httpport);
	xlog("update_simulator_balance req: " . $req);
	xlog("update_simulator_balance params: " . $params);
	xlog("update_simulator_balance request: " . $request);
	xlog("update_simulator_balance response: " . $response);
}

function user_alert($agentId, $soundId, $text)
{
	xlog("user_alert: " . $agentId);
	xlog("user_alert: " . $soundId);
	xlog("user_alert: " . $text);
    $db = new DB;
    $sql = "select serverIP, serverHttpPort, regionSecret from Presence ".
			"inner join regions on regions.uuid = Presence.RegionID ".
			"where Presence.UserID = '".$db->escape($agentId)."'";
    
    $db->query($sql);

    $results = $db->next_record();
    if ($results)
    {
        $serverIp = $results["serverIP"];
        $httpport = $results["serverHttpPort"];
		$secret   = $results["regionSecret"];
        
        
        $req = array('agentId'=>$agentId, 'soundID'=>$soundId,
				'text'=>$text, 'secret'=>$secret);

        $params = array($req);

        $request = xmlrpc_encode_request('userAlert', $params);
        $response = do_call($serverIp, $httpport, $request);
    }
	xlog("user_alert serverIp: " . $serverIp);
	xlog("user_alert httpport: " . $httpport);
	xlog("user_alert secret: " . $secret);
	
	xlog("user_alert req: " . $req);
	
	xlog("user_alert params: " . $params);
	xlog("user_alert request: " . $request);
	xlog("user_alert response: " . $response);
}

function move_money($sourceId, $destId, $amount, $aggregatePermInventory, $aggregatePermNextOwner, $flags, $transactionType, $description, $regionGenerated,$ipGenerated)
{
	xlog("move_money sourceId: " . $sourceId);
	xlog("move_money destId: " . $destId);
	xlog("move_money amount: " . $amount);
	xlog("move_money aggregatePermInventory: " . $aggregatePermInventory);
	xlog("move_money aggregatePermNextOwner: " . $aggregatePermNextOwner);
	xlog("move_money flags: " . $flags);
	xlog("move_money transactionType: " . $transactionType);
	xlog("move_money description: " . $description);
	xlog("move_money regionGenerated: " . $regionGenerated);
	xlog("move_money ipGenerated: " . $ipGenerated);
	
	$db = new DB;
	
	// select current region
	$sql = "select RegionID from Presence " . "where UserID = '".$db->escape($destId)."'";
    
    $db->query($sql);

    $results = $db->next_record();
    if ($results)
    {
        $currentRegion = $results["currentRegion"];
	}
	xlog("move_money currentRegion: " . $currentRegion);
}

function get_balance($avatarId)
{
	xlog("get_balance: " . $avatarId);
    $db=new DB;

    $cash = 0;

    return (integer)$cash;
}

function do_call($host, $port, $request)
{
	xlog("do_call: " . $host . $port . $request);
    $url = "http://$host:$port/";
    $header[] = "Content-type: text/xml";
    $header[] = "Content-length: ".strlen($request);
    
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    
    $data = curl_exec($ch);       
    if (!curl_errno($ch))
	{
        curl_close($ch);
        return $data;
    }
}

function agent_name($agentId)
{
	xlog("agent_name: " . $agentId);
	$db=new DB;

	$sql="select FirstName, LastName from UserAccounts where PrincipalID='".$agentId."'";
	$db->query($sql);

	$record=$db->next_record();
	if(!$record)
		return "";

	$name=implode(" ", array($record[0], $record[1]));

	return $name;
	xlog("agent_name: " . $name);
}

//
// The XMLRPC server object
//

$xmlrpc_server = xmlrpc_server_create();
xlog("xmlrpc_server_create");
//
// Viewer communications section
//
// Functions in this section are called by the viewer directly. Names and
// parameters are determined by the viewer only.
//

//
// Viewer retrieves currency buy quote
//

xmlrpc_server_register_method($xmlrpc_server, "getCurrencyQuote", "get_currency_quote");

function get_currency_quote($method_name, $params, $app_data)
{
	xlog("get_currency_quote: " . $method_name . $params . $app_data);
	//$confirmvalue = "1234567883789";
	$confirmvalue = "123456789";
	//$confirmvalue = "";

	$req       = $params[0];

	$agentid   = $req['agentId'];
	$sessionid = $req['secureSessionId'];
	$amount    = $req['currencyBuy'];
	
	xlog("get_currency_quote req: " . $req);
	xlog("get_currency_quote agentid: " . $agentid);
	xlog("get_currency_quote sessionid: " . $sessionid);
	xlog("get_currency_quote amount: " . $amount);

	//
	// Validate Requesting user has a session
	//

	$db = new DB;
	$db->query("select UserID from Presence where "."UserID='". $db->escape($agentid)."' and "."SecureSessionID='".$db->escape($sessionid)."'");
	
	// Prozeduraler Stil
	//$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	//mysqli_query($link, "select UserID from Presence where "."UserID='". $db->escape($agentid)."' and "."SecureSessionID='".$db->escape($sessionid)."'");
	
	list($UUID) = $db->next_record(); // next_record() aus der DB Klasse wird aufgerufen

	if($UUID)
	{
		$estimatedcost = convert_to_real($amount);
		
		$currency = array('estimatedCost'=>$estimatedcost,
				'currencyBuy'=>$amount);
		
		header("Content-type: text/xml");
		$response_xml = xmlrpc_encode(array(
				'success'  => True,
				'currency' => $currency,
				'confirm'  => $confirmvalue));

		print $response_xml;
	}
	else
	{
		header("Content-type: text/xml");
		$response_xml = xmlrpc_encode(array(
				'success'      => False,
				'errorMessage' => "Unable to Authenticate\n\nClick URL for more info. 002",
				'errorURI'     => "".SYSURL.""));

		print $response_xml;
	}

	return "";
}

//
// Viewer buys currency
//

xmlrpc_server_register_method($xmlrpc_server, "buyCurrency", "buy_currency");

function buy_currency($method_name, $params, $app_data)
{
	xlog("buy_currency: " . $method_name . $params . $app_data);
	global $economy_source_account;
	global $minimum_real;
	global $low_amount_error;

	$req       = $params[0];

	$agentid   = $req['agentId'];
	//$regionid  = $req['regionId'];
	$regionid       = $req['RegionID'];
	$sessionid = $req['secureSessionId'];
	$amount    = $req['currencyBuy'];
	$ipAddress = $_SERVER['REMOTE_ADDR'];
	
	xlog("buy_currency agentid: " . $agentid);
	xlog("buy_currency regionid: " . $regionid);
	xlog("buy_currency sessionid: " . $sessionid);
	xlog("buy_currency amount: " . $amount);
	xlog("buy_currency ipAddress: " . $ipAddress);

	//
	// Validate Requesting user has a session
	//

	$db = new DB;
	$db->query("select UserID from Presence where ".
			"UserID='".           $db->escape($agentid).  "' and ".
			"SecureSessionID='".$db->escape($sessionid)."'");

	list($UUID) = $db->next_record();

	if($UUID)
	{
		$cost = convert_to_real($amount);

		if($cost < $minimum_real)
		{
			$error=sprintf($low_amount_error, $minimum_real/100.0);

			header("Content-type: text/xml");
			$response_xml = xmlrpc_encode(array(
					'success'      => False,
					'errorMessage' => $error,
					'errorURI'     => "".SYSURL.""));

			print $response_xml;

			return "";
		}

		$transactionResult = process_transaction($agentid,$cost,$ipAddress);
		
		if ($transactionResult)
		{
			header("Content-type: text/xml");
			$response_xml = xmlrpc_encode(array(
					'success' => True));

			print $response_xml;

			move_money($economy_source_account, $agentid, $amount, 0, 0, 0, 0, "Currency purchase", $regionid, $ipAddress);

			update_simulator_balance($agentid);
		}
		else
		{
			header("Content-type: text/xml");
			$response_xml = xmlrpc_encode(array(
					'success'      => False,
					'errorMessage' => "We were unable to process the transaction.  The gateway denied your charge",
					'errorURI'     => "".SYSURL.""));

			print $response_xml;
		}
	}
	else 
	{
		header("Content-type: text/xml");
		$response_xml = xmlrpc_encode(array(
					'success'      => False,
					'errorMessage' => "Unable to Authenticate\n\nClick URL for more info. 003",
					'errorURI'     => "".SYSURL.""));
		print $response_xml;
	}
	
	return "";
}

//
// Region communications section
//
// Functions in this section are called by the region server
//

//
// Region requests account balance
//

xmlrpc_server_register_method($xmlrpc_server, "simulatorUserBalanceRequest", "balance_request");

function balance_request($method_name, $params, $app_data)
{
	xlog("balance_request" .$method_name . $params . $app_data);
	$req            = $params[0];

	$agentid        = $req['agentId'];
	$sessionid      = $req['secureSessionId'];
	//$regionid       = $req['regionId'];
	$regionid       = $req['RegionID'];
	$secret         = $req['secret'];
	$currencySecret = $req['currencySecret'];

    //
    // Validate region secret
    //

    $db=new DB;
    $sql="select uuid from regions ".
            "where uuid='".  $db->escape($regionid)."' and ".
            "regionSecret='".$db->escape($secret)  ."'";

    $db->query($sql);

    list($region_id) = $db->next_record();

    if ($region_id)
    {
        // We have a region, check agent session

        $sql = "select UserID from Presence ".
                "where UserID='".     $db->escape($agentid)  ."' and ".
                "SecureSessionID='".$db->escape($sessionid)."' and ".
                "RegionID='".  $db->escape($regionid) ."'";

        $db->query($sql);
        list($user_id) = $db->next_record();

        if($user_id)
        {
            $response_xml = xmlrpc_encode(array(
                    'success' => True,
                    'agentId' => $agentid,
                    'funds'   => (integer)get_balance($agentid)));
        }
        else
        {
            $response_xml = xmlrpc_encode(array(
                    'success'      => False,
                    'errorMessage' => "Could not authenticate your avatar. Money operations may be unavailable",
                    'errorURI'     => " "));
        }
    }
    else
    {
        $response_xml = xmlrpc_encode(array(
                'success'      => False,
                'errorMessage' => "This region is not authorized to check your balance. Money operations may be unavailable",
                'errorURI'     => " "));
    }

    header("Content-type: text/xml");
    print $response_xml;

    return "";
}

//
// Region initiates money transfer
//

xmlrpc_server_register_method($xmlrpc_server, "regionMoveMoney", "region_move_money");

function region_move_money($method_name, $params, $app_data)
{
	xlog("region_move_money method_name" . $method_name);
	xlog("region_move_money params" . $params);
	xlog("region_move_money app_data" . $app_data);
	global $economy_sink_account;

	$req                    = $params[0];
	$agentid                = $req['agentId'];
	$sessionid              = $req['secureSessionId'];
	$regionid               = $req['regionId'];
	//$regionid       = $req['RegionID'];
	$secret                 = $req['secret'];
	$currencySecret         = $req['currencySecret'];
	$destid                 = $req['destId'];
	$cash                   = $req['cash'];
	$aggregatePermInventory = $req['aggregatePermInventory'];
	$aggregatePermNextOwner = $req['aggregatePermNextOwner'];
	$flags                  = $req['flags'];
	$transactiontype        = $req['transactionType'];
	$description            = $req['description'];
	$ipAddress              = $_SERVER['REMOTE_ADDR'];

	xlog("region_move_money method_name" . $method_name);
	xlog("region_move_money method_name" . $req);
	xlog("region_move_money method_name" . $agentid);
	xlog("region_move_money method_name" . $sessionid);
	xlog("region_move_money method_name" . $regionid);
	xlog("region_move_money method_name" . $secret);
	xlog("region_move_money method_name" . $currencySecret);
	xlog("region_move_money method_name" . $destid);
	xlog("region_move_money method_name" . $cash);
	xlog("region_move_money method_name" . $aggregatePermInventory);
	xlog("region_move_money method_name" . $aggregatePermNextOwner);
	xlog("region_move_money method_name" . $flags);
	xlog("region_move_money method_name" . $transactiontype);
	xlog("region_move_money method_name" . $description);
	xlog("region_move_money method_name" . $ipAddress);
	
	
    //
    // Validate region secret
    //

    $db=new DB;
    $sql="select uuid from regions ".
            "where uuid='".  $db->escape($regionid)."' and ".
            "regionSecret='".$db->escape($secret)  ."'";

    $db->query($sql);

    list($region_id) = $db->next_record();

    if ($region_id)
    {
        // We have a region, check agent session

        $sql = "select UserID from Presence " . "where UserID='" . $db->escape($agentid)  . "' and " . "SecureSessionID='" . $db->escape($sessionid) . "' and " . "RegionID='" . $db->escape($regionid) . "'";

        $db->query($sql);
        list($user_id) = $db->next_record();

        if($user_id)
        {
			if(get_balance($agentid) < $cash)
			{
				$response_xml = xmlrpc_encode(array('success' => False, 'errorMessage' => "You do not have sufficient funds for this purchase", 'errorURI' => " "));
			}
			else
			{
				if($destid == "00000000-0000-0000-0000-000000000000")
					$destid=$economy_sink_account;

				if($transactiontype == 1101)
				{
					user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid L$".$cash." to upload.");
					$description="Asset upload fee";
				}
				else if($transactiontype == 5001) 
				{
					$destName=agent_name($destid);
					$sourceName=agent_name($agentid);

					user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid ".$destName." L$".$cash);
					user_alert($destid, "00000000-0000-0000-0000-000000000000", $sourceName." paid you L$".$cash);
				
					$description="Gift";
				}
				else if($transactiontype == 5008) 
				{
					$destName=agent_name($destid);
					$sourceName=agent_name($agentid);

					user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid ".$destName." L$".$cash);
					user_alert($destid, "00000000-0000-0000-0000-000000000000", $sourceName." paid you L$".$cash);
				}
				else if($transactiontype == 2) 
				{
					$destName=agent_name($destid);
					$sourceName=agent_name($agentid);

					user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid ".$destName." L$".$cash);
					user_alert($destid, "00000000-0000-0000-0000-000000000000", $sourceName." paid you L$".$cash);
				}
				else if($transactiontype == 0) 
				{

					if($destid == $economy_sink_account)
					{
						user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid L$".$cash." for a parcel of land.");
					}
					else
					{
						$destName=agent_name($destid);
						$sourceName=agent_name($agentid);

						user_alert($agentid, "00000000-0000-0000-0000-000000000000", "You paid ".$destName." L$".$cash." for a parcel of land.");
						user_alert($destid, "00000000-0000-0000-0000-000000000000", $sourceName." paid you L$".$cash." for a parcel of land");
					}
				
					$description="Land purchase";
				}

				move_money($agentid, $destid, $cash, $aggregatePermInventory, $aggregatePermNextOwner, $flags, $transactiontype, $description, $regionid, $ipAddress);
			
				$response_xml = xmlrpc_encode(array(
						'success'        => True,
						'agentId'        => $agentid,
						'funds'          => get_balance($agentid),
						'funds2'         => get_balance($destid),
						'currencySecret' => " "));
			}
		}
		else
		{
			$response_xml = xmlrpc_encode(array(
					'success'      => False,
					'errorMessage' => "Unable to authenticate avatar. Money operations may be unavailable 004",
					'errorURI'     => " "));
		}
	}
	else
	{
		$response_xml = xmlrpc_encode(array(
				'success'      => False,
				'errorMessage' => "This region is not authorized to manage your money. Money operations may be unavailable",
				'errorURI'     => " "));
	}

	header("Content-type: text/xml");
	print $response_xml;
	
	$stri = update_simulator_balance($agentid);
	$stri = update_simulator_balance($destid);

	return "";
}

//
// Region claims user
//

xmlrpc_server_register_method($xmlrpc_server, "simulatorClaimUserRequest", "claimUser_func");

function claimUser_func($method_name, $params, $app_data)
{
	xlog("claimUser_func" . $method_name . $params . $app_data);
	$req       = $params[0];
	$agentid   = $req['agentId'];
	$sessionid = $req['secureSessionId'];
	//$regionid  = $req['regionId'];
	$regionid       = $req['RegionID'];
	$secret    = $req['secret'];
	
    //
    // Validate region secret
    //

    $db=new DB;
    $sql="select uuid from regions ".
            "where uuid='".  $db->escape($regionid)."' and ".
            "regionSecret='".$db->escape($secret)  ."'";

    $db->query($sql);

    list($region_id) = $db->next_record();

    if ($region_id)
    {
        // We have a region, check agent session

        $sql = "select UserID from Presence ".
                "where UserID='".     $db->escape($agentid)  ."' and ".
                "SecureSessionID='".$db->escape($sessionid);

        $db->query($sql);
        list($user_id) = $db->next_record();

        if($user_id)
        {
			$sql="update Presence set ".
					"RegionID='".$db->escape($regionid)."' ".
					"where UserID='".   $db->escape($agentid) ."'";

			$db->query($sql);

			$db->next_record();
			$response_xml = xmlrpc_encode(array(
					'success'        => True,
					'agentId'        => $agentid,
					'funds'          => (integer)get_balance($agentid),
					'currencySecret' => " "));
		}
		else
		{
			$response_xml = xmlrpc_encode(array(
					'success'      => False,
					'errorMessage' => "Unable to authenticate avatar. Money operations may be unavailable 001",
					'errorURI'     => " "));
		}
	}
	else
	{
		$response_xml = xmlrpc_encode(array(
				'success'      => False,
				'errorMessage' => "This region is not authorized to manage your money. Money operations may be unavailable",
				'errorURI'     => " "));
	}

	header("Content-type: text/xml");
	print $response_xml;
	
	return "";
}

//
// Process the request
//

//if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
//$server->service($HTTP_RAW_POST_DATA);

$request_xml = file_get_contents('php://input');
//$request_xml = $HTTP_RAW_POST_DATA;
//$request_xml = file_get_contents("php://input");
xmlrpc_server_call_method($xmlrpc_server, $request_xml, '');
xmlrpc_server_destroy($xmlrpc_server);
?>