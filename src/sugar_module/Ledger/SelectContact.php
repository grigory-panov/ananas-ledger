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
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Ledger/AnanasContacts.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/TimeDate.php');
$timedate = new TimeDate();
global $app_strings;
global $app_list_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'Ledger');

$today = $timedate->to_db_date(date("Y-m-d")); 
$today = $timedate->handle_offset($today, $timedate->dbDayFormat, true);

$ListView = new ListView();
$seedTasks = new AnanasContacts();
//$seedTasks->drop_tables();
//$seedTasks->create_tables();
//$seedTasks = new Task();
//$where = "tasks.assigned_user_id='". $current_user->id ."' and (tasks.status is NULL or (tasks.status!='Completed' and tasks.status!='Deferred')) ";
//$where .= "and (tasks.date_start is NULL or tasks.date_start <= '$today')";
$ListView->initNewXTemplate( 'modules/Ledger/SelectContact.html',$current_module_strings);
$header_text = '';

$ListView->setHeaderTitle($current_module_strngs['LBL_SELECT_CONTACT'].$header_text);
$ListView->processListView($seedTasks, "main", "DOC");
?>
