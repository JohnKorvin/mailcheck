<?

function page_header($title="Конкурсное задание") {
	
	echo "<!doctype html>\n";
	echo "<html lang='ru-RU'>\n<head>\n";
	echo "\t<title>".$title."</title>";
	
	echo "\n\t<script src='https://code.jquery.com/jquery-3.4.1.min.js' type='text/javascript'></script>";
	echo "\n\t<script src='/src/js/app.js' type='text/javascript'></script>";
	
	echo "<link href='/src/css/style.css' rel='stylesheet' type='text/css' />";
	
	echo "\n</head>";
	echo "\n<body>";
}

function page_footer() {
	echo "</body>";
	echo "</html>";

}
