<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /home/app/tmp/cvsroot-ananas/Ledger/src/sugar_module/Ledger/update.php,v 1.3 2005-12-02 15:24:06 gr Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Ledger/DocJournal.php');
require_once('include/logging.php');
$focus = new DocJournal();
//$focus->drop_tables();
//$focus->create_tables();

$client = new SoapClient(NULL,
        array( "location" => "http://localhost:80/ledger",
			   "uri"      => "ledger",
			   "style"    => SOAP_RPC,
			   "use"      => SOAP_ENCODED,
			   "trace"	  => 1,
			   "exceptions" => 0
			   
			 )); 
$login = "ibm";
$password ="e223";
$log_name = "/tmp/sugar_ananas.log";
if($client->__call( "login", array( 	new SoapParam($log_name,"param1"),
					new SoapParam($login,"param2" ),
				       	new SoapParam($password,"param3")),
	        array("uri" => "ledger",
		"soapaction" => "")))
{
	print ("autentification ok<br>\n");		

	$rc = "/home/gr/devel/ananas-engine-qt/applications/inventory/inventory.rc";
	$journ ="DocJournal.Журнал прихода"; 
	$kindIfDoc = 1;
	$datStart = date;
	$datEnd = date;
	try
	{
	$res = $client->__call( "getDocumentList",
				array(	new SoapParam($log_name,"param1"),
					new SoapParam($rc,"param2"),
					new SoapParam($joun,"param3"),
					new SoapParam($kindOfDoc,"param4"),
					new SoapParam($datStart,"param5"),
					new SoapParam($datEnd,"param6")),
				array("uri" => "ledger",
					"soapaction" => "")); 
					
	var_dump($res);
	print "<pre>\n"; 
	print "Request :\n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
	print "Response:\n".htmlspecialchars($client->__getLastResponse())."\n"; 
	print "</pre>";
	}
	catch(SoapFault $exception)
	{ 
	    echo $exception;       
	}  
	
//	$sent = split(" ",$res["listDoc"]);
	for($i =0; $i < count($res->item);$i++)
	{
		print("!!!!!!!!!!!!!!!!!!<br>\n");
		print(count($res->item));
		print("<br>");
		$exists = false;
		for($j=0; $j< count($res->item[$i]->item); $j++)
		{
			print($res->item[$i]->item[$j]->fieldName);
			$tmp = $res->item[$i]->item[$j]->fieldName;
			if($tmp=="idd")
			{
				$focus->ananasid = $res->item[$i]->item[$j]->val;
				$exists = $res->item[$i]->item[$j]->val;
			}
			if($tmp=="pnum")
			{
				$focus->name = $res->item[$i]->item[$j]->val;
			}
			if($tmp=="id")
			{
				$focus->id = $res->item[$i]->item[$j]->val;
			}
			print(" \n");
			print($res->item[$i]->item[$j]->val);
			print("<br>\n");
		}
	//		$focus->ananasid = $res->item[$i]->item["id"]->;
	//	$focus->id = $res[$i]["value"];
	//	$focus->retrieve($res[$i]["value"]);
	//	$focus->name = $res[$i]["value"];
		$check_notify = FALSE;
//		$res = false;	
		foreach($focus->column_fields as $field)
		{
			if(isset($_POST[$field]))
			{
				$value = $_POST[$field];
				$focus->$field = $value;
		//		$res =true;
			}
		}
		if($focus->check_exist($exists)==0)
		{
			$focus->new_with_id = true;
		}
		else
		{
			$focus->new_with_id = false;
		}
		$focus->save();
		
//	print("documnet id is {$sent[$i]}<br>\n"); 
	}
}
else
{
	print ("autentification fail<br>\n");
}
unset($soapclient);
header("Location: index.php?module=Ledger&action=index");
?>
