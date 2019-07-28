<?

include "funcs.php";

if( $_GET['type'] == "subscriber" && isset($_GET['val']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	$result = array();
	$val = explode(" ", addslashes(strip_tags(trim($_GET['val']))) );
	
	$r = my_query("SELECT `id`, `fio` FROM `subscriber` WHERE `fio` LIKE '%".implode("%' && `fio` LIKE '%",$val)."%' GROUP BY `id` LIMIT 100");
	if( $r && ($result["size"] = mysql_num_rows($r)) > 0 ) {
		while( $o = mysql_fetch_assoc($r) ) {
			$result["items"][$o["id"]] = $o["fio"];
		}
	}
	
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	exit;
} else if( $_GET['type'] == "magazine" && isset($_GET['val']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	$result = array();
	$val = explode(" ", addslashes(strip_tags(trim($_GET['val']))) );
	
	$r = my_query("SELECT `id`, `name`, `number` FROM `magazine` WHERE `name` LIKE '%".implode("%' && `name` LIKE '%",$val)."%' GROUP BY `id` LIMIT 100");
	if( $r && ($result["size"] = mysql_num_rows($r)) > 0 ) {
		while( $o = mysql_fetch_assoc($r) ) {
			$result["items"][$o["id"]] = $o["name"]." #".$o["number"];
		}
	}
	
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	exit;
	
} else if( isset($_POST["update-check"]) && ($id=(int)$_GET['id']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	my_query("
		UPDATE `check` SET
			`id_subscriber` = ".(int)$_GET['subscriber'].",
			`id_magazine`	= ".(int)$_GET['magazine'].",
			
			`number`		= ".(int)$_GET['number'].",
			`sent_date`		= ".(int)preg_replace('#[^0-9]+#','',$_GET['sent_date']).",
			`track`			= ".(int)$_GET['track'].",
			`status`		= '1',
			
			`updated`		= '".date("Y-m-d")."'
		WHERE
			`id`= ".$id." && `status`=0
	");
	
	$result = array("update"=>true);
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	exit;

} else if( isset($_GET['report']) ) {
	
	$ot = $_GET['ot'] ? $_GET['ot'] : date('Y.m.d',strtotime("-1 month"));
	$do = $_GET['do'] ? $_GET['do'] : date('Y.m.d');
	
	$_ot = preg_replace('#[^0-9]+#','',$ot);
	$_do = preg_replace('#[^0-9]+#','',$do);
	
	page_header("Отчёт");
	
	echo "<a href='/'>&larr; Back</a><br><br>";
	
	showFilterReport1($ot, $do);
	echo '<br><br>';
	
	$r = my_query('SELECT *, COUNT(*) `cnt_check` FROM `check` WHERE `created`>='.(int)$_ot.' && `created`<='.(int)$_do.' GROUP BY `created` LIMIT 100');
	
	showReport1($r);
	
} else {

	page_header();

	echo "<center><a href='/?report'>Сформировать отчёт</a></center>";

	echo "<hr><br>";

	createCheckPack();

	$r = my_query("SELECT * FROM `check` WHERE `status`=0 ORDER BY `created` LIMIT 100");
	showCheckPack($r);
}

if( $root && isset($_GET['recreate-db']) ) {
	
	my_query("DROP TABLE IF EXISTS `subscriber`");
	my_query("CREATE TABLE `subscriber` (
			`id`		INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`fio`   	TEXT NOT NULL,
			`adr`		TEXT NOT NULL,
			
			PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	my_query("DROP TABLE IF EXISTS `magazine`");
	my_query("CREATE TABLE `magazine` (
			`id`		INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`name`   	TEXT NOT NULL,
			`number`    INT NOT NULL,
			
			`release_date`	DATE NOT NULL,
			
			PRIMARY KEY (`id`),
			KEY (`number`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	my_query("DROP TABLE IF EXISTS `mailing_list`");
	my_query("CREATE TABLE `mailing_list` (
			`id`			INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_subscriber`	INT UNSIGNED NOT NULL,
			`act_date`   	DATE NOT NULL,
			`period`    	INT(2) UNSIGNED NOT NULL,
			
			PRIMARY KEY (`id`),
			KEY (`id_subscriber`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	my_query("DROP TABLE IF EXISTS `check`");
	my_query("CREATE TABLE `check` (
			`id`			INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_subscriber`	INT UNSIGNED NOT NULL,
			`id_magazine`	INT UNSIGNED NOT NULL,
			`number`		INT UNSIGNED NOT NULL,
			`sent_date`		DATE NOT NULL,
			`track`			INT UNSIGNED NOT NULL,
			
			`imgext`		VARCHAR(5) NOT NULL,
			
			`status`		INT(3) UNSIGNED NOT NULL,
			
			`updated`   	DATE NOT NULL,
			`created`   	DATE NOT NULL,
			
			PRIMARY KEY (`id`),
			KEY (`track`),
			KEY (`status`),
			KEY (`id_subscriber`),
			KEY (`id_magazine`),
			KEY (`updated`),
			KEY (`created`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	echo "recreate-db";

}

page_footer();
