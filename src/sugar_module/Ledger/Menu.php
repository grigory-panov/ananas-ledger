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
 * $Header: /home/app/tmp/cvsroot-ananas/Ledger/src/sugar_module/Ledger/Menu.php,v 1.5 2005-05-10 12:27:10 gr Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
$module_menu = Array(
	Array("index.php?module=Ledger&action=recreate", $mod_strings['LNK_RECREATE'],"AnanasRecreate"),
	Array("index.php?module=Ledger&action=update", $mod_strings['LNK_UPDATE'],"AnanasUpdate"),
	Array("index.php?module=Ledger&action=SelectContact", $mod_strings['LNK_ASSIGN_CONTACT'],"AnanasAssignContact"),
	);

?>
