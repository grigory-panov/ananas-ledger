<?php
//require_once('nusoap.php');

// transport classes


	$home_xtpl = new XTemplate('modules/Ledger/Modules.html');
	$panels = array(
	//	'MYAPPOINTMENTS'=> "modules/Activities/OpenListView.php",
//		'MYOPPORTUNITIES'=> "modules/Opportunities/ListViewTop.php",
//		'MYCASES' =>"modules/Cases/MyCases.php",
		'DOCUMENTS' => "modules/Ledger/Documents.php",
//		'MYLEADS' => "modules/Leads/MyLeads.php",
//		'MYBUGS' => 'modules/Bugs/MyBugs.php',
//		'MYCAL' => "modules/Calendar/small_month.php",
//		'MYPIPELINE' => "modules/Dashboard/Chart_my_pipeline_by_sales_stage.php",
		);
		$section = 'main';
		$old_contents = ob_get_contents();
		ob_end_clean();
		$processed= array();
		foreach($panels as $name=>$path){

			if( $home_xtpl->var_exists($section,$name)){
			ob_start();
			include($path);
			echo "<BR>\n";
			$temp =  ob_get_contents();
			$processed[$name] = $temp;
			ob_end_clean();
		}}
		ob_start();
		echo $old_contents;
		foreach($processed as $name=>$val){

			$home_xtpl->assign($name, $val);
		}
		global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	echo "<a href='index.php?action=index&module=DynamicLayout&from_action=Home&from_module=Home'>".get_image($image_path."EditLayout.png","border='0' alt='Edit Layout' align='bottom'")."</a>";
}

		$home_xtpl->parse($section);
		$home_xtpl->out($section);

// optional add-on classes
?>
