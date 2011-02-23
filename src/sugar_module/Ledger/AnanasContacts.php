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
 * $Header: /home/app/tmp/cvsroot-ananas/Ledger/src/sugar_module/Ledger/AnanasContacts.php,v 1.2 2005-05-11 11:46:49 gr Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
//require_once('include/upload_file.php');

// Note is used to store customer information.
class AnanasContacts extends SugarBean
{
// Task is used to store customer information.
        var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $ananasid;
	var $description;
	var $name;
	var $status;
	var $date_due_flag;
	var $date_due;
	var $time_due;
	var $date_start_flag;
	var $date_start;
	var $time_start;
	var $priority;
	var $parent_type;
	var $parent_id;
	var $contact_id;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $assigned_user_name;
	var $required_fields = array('name'=>1);
	var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "ananascontacts";

	var $object_name = "contact";

	var $column_fields = Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"
		
		, "ananasid"
		, "description"
		, "name"
		, "status"
		, "date_due"
		, "time_due"
		, "date_start_flag"
		, "date_start"
		, "time_start"
		, "priority"
		, "date_due_flag"
		, "parent_type"
		, "parent_id"
		, "contact_id"
		);
/*
	DO NOT DEPEND ON THIS THIS DATA STRUCTURE
	IT IS ONLY TEMPORARY AND MAY NOT BE SUPPORTED IN FUTURE RELEASES
*/
		var $field_defs = array(
               array("name"=>"name", "vname"=>"LBL_SUBJECT","type"=>"varchar","len"=>"255" ),
//                array('name'=> 'parent_name', 'parent_type'=>'record_type_display' , 'type_name'=>'parent_type','id_name'=>'parent_id', 'vname'=>'LBL_RELATED_TO', 'type'=>'parent'),



                array('name'=>'first_name','rname'=>'first_name','id_name'=>
		 	'contact_id','vname'=>'LBL_CONTACT_FIRST_NAME','type'=>'relate','table'=>'contacts',
			'isnull'=>'true','module'=>'Contacts'),
                array('name'=>'last_name','rname'=>'last_name','id_name'=>
		 	'contact_id','vname'=>'LBL_CONTACT_LAST_NAME','type'=>'relate','table'=>'contacts',
			'isnull'=>'true','module'=>'Contacts'),
                array('name'=>'date_entered','vname'=>'LBL_DATE_ENTERED','type'=>'date'),
                array('name'=>'date_modified','vname'=>'LBL_DATE_MODIFIED','type'=>'date'),
                array('name'=>'date_due','vname'=>'LBL_DUE_DATE','type'=>'date', 'rel_field'=>'time_due'),
                 array('name'=>'time_due','vname'=>'LBL_DUE_TIME','type'=>'time', 'rel_field'=>'date_due'),
                array('name'=>'date_due_flag','vname'=>'LBL_DATE_DUE_FLAG','type'=>'bool'),
                array('name'=>'date_start','vname'=>'LBL_START_DATE','type'=>'date', 'rel_field'=>'time_start'),
                 array('name'=>'time_start','vname'=>'LBL_START_TIME','type'=>'time', 'rel_field'=>'date_start'),
                array('name'=>'date_start_flag','vname'=>'LBL_DATE_START_FLAG','type'=>'bool'),
                array('name'=>'assigned_user_id','rname'=>'user_name','id_name'=>'assigned_user_id','vname'=>'LBL_ASSIGNED_TO','type'=>'assigned_user_name','table'=>'users', 'isnull'=>'false'),
                array("name"=>"priority", "vname"=>"LBL_PRIORITY",'type'=>'enum' ,
                                'options'=>'task_priority_dom'),
                array("name"=>"status", "vname"=>"LBL_STATUS",'type'=>'enum' ,
                                'options'=>'task_status_dom'),
                array("name"=>"description",
                        "vname"=>"LBL_DESCRIPTION","type"=>"varchar","len"=>"255" ),
                		        array('name'=>'created_by','rname'=>'user_name',
									'id_name'=>'created_by','vname'=>'LBL_CREATED',
									'type'=>'created_by','table'=>'users',
									'isnull'=>'false'),
						        array('name'=>'modified_user_id','rname'=>'user_name',
									'id_name'=>'modified_user_id','vname'=>'LBL_MODIFIED',
									'type'=>'modified_by_name','table'=>'users',
					'isnull'=>'false'),
                );


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');

	// This is the list of fields that are in the lists.
	var $list_fields = Array('id', 'status', 'name','ananasid', 'parent_type', 'parent_name', 'parent_id', 'date_due', 'contact_id', 'contact_name', 'assigned_user_name', 'assigned_user_id','first_name','last_name','time_due', 'priority'




		);

	function AnanasContacts() {
		$this->log = LoggerManager::getLogger('ledger');
		$this->db = new PearDatabase();

                $custom = new CustomFields();
                $custom->setFieldDefs($this); 
		foreach ($this->field_defs as $field)
                {
                        $this->field_name_map[$field['name']] = $field;
                }




	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;

		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', date_modified datetime NOT NULL';
		$query .=', assigned_user_id char(36)';
		$query .=', modified_user_id char(36)';
		$query .=', created_by char(36)';


		$query .=', ananasid char(30)';
		$query .=', name char(50)';
		$query .=', status char(25)';
		$query .=', date_due_flag char(5) default \'on\'';
		$query .=', date_due date';
		$query .=', time_due time';
		$query .=', date_start_flag char(5) default \'on\'';
		$query .=', date_start date';
		$query .=', time_start time';
		$query .=', parent_type char(25)';
		$query .=', parent_id char(36)';
		$query .=', contact_id char(36)';
		$query .=', priority char(25)';
		$query .=', description TEXT';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( ID ) )';



		$this->db->query($query,$app_strings['ERR_CREATING_TABLE']);

		// Create the indexes
		$this->create_index("create index idx_tsk_name on ". $this->table_name. " (name)");
		$this->create_index("create index idx_task_con_del on ". $this->table_name. " ( contact_id , deleted )");
		$this->create_index("create index idx_task_par_del on ". $this->table_name. " ( parent_id , parent_type , deleted )");
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;



		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
		//$custom_fields = new CustomFields();
                $query = "SELECT b.first_name";
                //$query .= $custom_fields->get_list_query_custom_select($this);
		$query .= " FROM contacts b, ". $this->table_name . " a";
		
		$query .= " where a.deleted=0 AND b.deleted=0 AND b.id <> a.id";
		
	/*	if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY 1";*/
		return $query;

	}

        function create_export_query(&$order_by, &$where)
        {
                $contact_required = ereg("contacts", $where);

                if($contact_required)
                {
                        $query = "SELECT *";
			$query .= " FROM ". $this->table_name ;
			$where_auto = $this->table_name . ".deleted=0";
                }

                if($where != "")
                        $query .= " where $where AND ".$where_auto;
                else
                        $query .= " where ".$where_auto;

                if($order_by != "")
                        $query .= " ORDER BY $order_by";
                else
		$query .= " ORDER BY ". $this->table_name  .".name";
                return $query;

        }
	function check_exist($aid)
	{
                if($aid)
                {
                        $query = "SELECT ananasid";
			$query .= " FROM ". $this->table_name ;
			$query .= " WHERE ananasid=". $aid;
                }
             	$res =  $this->db->query($query);
		$row = $this->db->fetchByAssoc($res);
		return $row;
		
	}


	function fill_in_additional_list_fields()
	{
//		$this->fill_in_additional_detail_fields();
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
/*		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		global $app_strings;

		if (isset($this->contact_id)) {
			require_once("modules/Contacts/Contact.php");
			$contact = new Contact();
			$query = "SELECT first_name, last_name, phone_work, email1 from $contact->table_name where id = '$this->contact_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->contact_name = return_name($row, 'first_name', 'last_name');
				if ($row['phone_work'] != '') $this->contact_phone = $row['phone_work'];
				if ($row['email1'] != '') $this->contact_email = $row['email1'];
			}
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_parent_fields();
		*/
	}

	function fill_in_additional_parent_fields()
	{
		/*global $app_strings;

		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);

			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true,$app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Issues") {
        	require_once("modules/Issues/Issue.php");
            $parent = new Issue();

            $query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query, TRUE, "Error filling in additional detail fields: ");
			$row = $this->db->fetchByAssoc($result);

			if (!is_null($row)) {
 				$this->parent_name = '';
				if (!empty($row['name'])) $this->parent_name .= stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}
*/
	}

	function get_list_view_data(){
		global $action, $currentModule, $focus, $current_module_strings, $app_list_strings, $image_path;
		$timedate = new TimeDate();
		$today = $timedate->handle_offset(date("Y-m-d H:i:s", time()), $timedate->dbDayFormat, true);
		$task_fields =$this->get_list_view_array();
		$date_due = $timedate->to_db_date($task_fields['DATE_DUE'],true);
		if (!empty($this->priority)) 
			$task_fields['PRIORITY'] = $app_list_strings['task_priority_dom'][$this->priority];
		if (isset($this->parent_type)) 
			$task_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status != "Completed" && $this->status != "Deferred" ) {
			$task_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((is_object($focus)) ? $focus->id : "") . "&action=EditView&module=Ledger&record=$this->id&status=Completed'>".get_image($image_path."close_inline.png","alt='Close' border='0'")."</a>";
		}
		if( $date_due	< $today){
			$task_fields['DATE_DUE']= "<font class='overdueTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}else if( $date_due	== $today ){
			$task_fields['DATE_DUE'] = "<font class='todaysTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}else{
			$task_fields['DATE_DUE'] = "<font class='futureTask'>".$task_fields['DATE_DUE'].' '.$task_fields['TIME_DUE']."</font>";
		}

		$task_fields['CONTACT_NAME']= return_name($task_fields,"FIRST_NAME","LAST_NAME");
		$task_fields['TITLE'] = '';
		if (!empty($task_fields['CONTACT_NAME'])) {
			$task_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'].": ".$task_fields['CONTACT_NAME'];
		}
		if (!empty($this->parent_name)) {
			$task_fields['TITLE'] .= "\n".$app_list_strings['record_type_display'][$this->parent_type].": ".$this->parent_name;
		}
		if (isset($task_fields['STATUS'])) {
                        $task_fields['STATUS'] = translate('task_status_dom', '', $task_fields['STATUS']);
                }

		return $task_fields;
	}

	function parse_additional_headers(&$list_form, $xTemplateSection) {

	}

	function list_view_parse_additional_sections(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function set_notification_body($xtpl, $task)
	{
		$xtpl->assign("DOCUMENT_SUBJECT", $task->name);
		$xtpl->assign("DOCUMENT_PRIORITY", $task->priority);
		$xtpl->assign("DOCUMENT_DUEDATE", $task->date_due . " " . $task->time_due);
		$xtpl->assign("DOCUMENT_STATUS", $task->status);
		$xtpl->assign("DOCUMENT_DESCRIPTION", $task->description);

		return $xtpl;
	}

}
?>
