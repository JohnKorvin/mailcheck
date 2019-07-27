<?

$root = true;

define('DB_NAME', 'mailcheck'); # Название БД
define('DB_USER', 'mailcheck'); # Имя пользователя БД
define('DB_PWD', ''); # Пароль к БД
define('DB_HOST', 'localhost'); # HOST для БД

define('SCAN_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/scan'); 
define('PACK_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/pack'); 
define('CHECK_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/check'); 