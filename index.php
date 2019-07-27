<?

include "funcs.php";

if( $_GET['type'] == "subscriber" && isset($_GET['val']) ) {
	
	header('Content-Type: application/json; charset=UTF-8');
	header('Cache-Control: no-cache');
	
	$result = array();
	$val = explode(" ", addslashes(strip_tags($_GET['val'])) );
	
	$r = my_query("SELECT `id`, `fio` FROM `subscriber` WHERE `fio` LIKE '%".implode("%' && `fio` LIKE '%",$val)."%' LIMIT 100");
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
	$val = explode(" ", addslashes(strip_tags($_GET['val'])) );
	
	$r = my_query("SELECT `id`, `name`, `number` FROM `magazine` WHERE `name` LIKE '%".implode("%' && `name` LIKE '%",$val)."%' LIMIT 100");
	if( $r && ($result["size"] = mysql_num_rows($r)) > 0 ) {
		while( $o = mysql_fetch_assoc($r) ) {
			$result["items"][$o["id"]] = $o["name"]." #".$o["number"];
		}
	}
	
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	exit;
	
} else if( isset($_GET["add-check"]) ) {
	
	my_query("
		INSERT INTO `check` SET
			`id_subscriber` = '".(int)$_GET['subscriber']."',
			`id_magazine`	= '".(int)$_GET['magazine']."',
			`number`		= '".(int)$_GET['number']."',
			`sent_date`		= '".(int)preg_replace('#[^0-9]+#','',$_GET['sent_date'])."',
			`track`			= '".(int)$_GET['track']."',
			
			`status`		= '".(int)$_GET['status']."',
			
			`created`		= '".date("Y-m-d H:i:s")."'
	");
	$id = mysql_insert_id();
	
	if( !is_dir(CHECK_DIR) ) mkdir(CHECK_DIR,0744,true);
	if( rename(PACK_DIR.'/'.$_GET['check'], CHECK_DIR.'/'.$id.strrchr($_GET['check'],'.')) ) {
		unlink(PACK_DIR.'/'.$_GET['check']);
	}
	
	exit;

} else if( isset($_GET['report']) ) {
	
	page_header("Отчёт");
	
	echo "<a href='/'>&larr; Back</a><br><br>";
	
	$r = my_query('SELECT *, COUNT(*) `cnt_check` FROM `check` GROUP BY `created` LIMIT 100');
	
	showReport1($r);
	
} else {

	page_header();

	echo "<center><a href='/?report'>Сформировать отчёт</a></center>";

	echo "<hr><br>";

	createCheckPack();

	showCheckPack();
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
			
			PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	my_query("DROP TABLE IF EXISTS `check`");
	my_query("CREATE TABLE `check` (
			`id`			INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_subscriber`	INT UNSIGNED NOT NULL,
			`id_magazine`	INT UNSIGNED NOT NULL,
			`number`		INT UNSIGNED NOT NULL,
			`sent_date`		DATE NOT NULL,
			`track`			INT UNSIGNED NOT NULL,
			
			`status`		INT(3) UNSIGNED NOT NULL,
			
			`created`   	DATE NOT NULL,
			
			PRIMARY KEY (`id`),
			KEY (`track`),
			KEY (`status`),
			KEY (`id_subscriber`),
			KEY (`id_magazine`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	
	echo "recreate-db";

}

page_footer();
