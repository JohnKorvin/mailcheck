<?

include "class.funcs.php";

if( $_GET['type'] == "subscriber" && isset($_GET['val']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	echo MailChack::getJSONSubscriber($val);
	exit;
} else if( $_GET['type'] == "magazine" && isset($_GET['val']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	echo MailChack::getJSONMagazine($val);
	exit;
	
} else if( isset($_POST["update-check"]) && ($id=(int)$_GET['id']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	echo MailChack::updateCheck($id, $_GET);
	exit;

} else if( isset($_GET['report']) ) {
	
	$ot = $_GET['ot'] ? $_GET['ot'] : date('Y.m.d',strtotime("-1 month"));
	$do = $_GET['do'] ? $_GET['do'] : date('Y.m.d');
	
	$_ot = preg_replace('#[^0-9]+#','',$ot);
	$_do = preg_replace('#[^0-9]+#','',$do);
	
	page_header("Отчёт");
	
	echo "<a href='/class.index.php'>&larr; Back</a><br><br>";
	
	MailChack::showFilterReport1($ot, $do);
	echo '<br><br>';
	
	$r = my_query('SELECT *, COUNT(*) `cnt_check` FROM `check` WHERE `created`>='.(int)$_ot.' && `created`<='.(int)$_do.' GROUP BY `created` LIMIT 100');
	
	MailChack::showReport1($r);
	
} else {

	page_header();

	echo "<center><a href='/class.index.php?report'>Сформировать отчёт</a></center>";

	echo "<hr><br>";

	MailChack::createCheckPack();

	$r = my_query("SELECT * FROM `check` WHERE `status`=0 ORDER BY `created` LIMIT 100");
	MailChack::showCheckPack($r);
}

page_footer();
