<?

$root = true;

define('DB_NAME', 'purmo_mailcheck'); # Название БД
define('DB_USER', 'purmo_mailcheck'); # Имя пользователя БД
define('DB_PWD', 'dPstGfu8'); # Пароль к БД
define('DB_HOST', 'localhost'); # HOST для БД

define('SCAN_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/scan'); 
define('PACK_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/pack'); 
define('CHECK_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/check'); 