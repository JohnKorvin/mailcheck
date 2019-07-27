<?

/**********************************************
 *                                            *
 *        Функции по работе с базой           *
 *                                            *
 **********************************************/

$db = '';

function my_connect() {
	global $db;
	if ( !($db = mysql_connect(DB_HOST, DB_USER, DB_PWD)) ) {
		echo "<HTML><BODY><CENTER>Произошла ошибка доступа к базе данных.<BR> Приносим свои извинения.</CENTER></BODY></HTML>\n";
		exit;
	}
	my_query('set names utf8;');
}

function my_disconect() {
	global $db;
	if( $db ) mysql_close($db);
	$db='';
}

function my_query($query_string) {
	global $db;
	if( !$db ) my_connect();
	
	$result = mysql_db_query(DB_NAME, $query_string, $db);
	return $result;
}

function dmy_query($query_string) {
	
	$result = my_query($query_string);

	echo "&nbsp;<TABLE border=0 cellspacing=0 cellpadding=4 bgcolor=#999999 width=100%>";
	echo "<TR><TH align='left' style='color:#ffff00;'>".$query_string."</TH></TR>";
	if (mysql_errno())
		echo "<TR><TD bgcolor=#bb9999>Ошибка: " . mysql_error();
	else {
		echo "<TR><TD bgcolor=#9999bb>\n";
		if ($result==1)
			echo "Запрос выполнен.";
		elseif ($result!='')
			echo "Строк в ответе: ".mysql_num_rows($result).", $result";
		else
			echo "Ответ - пустой!\n";
	}
	echo "</TD></TR></TABLE>\n";


	return $result;
}
