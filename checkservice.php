<?

/**
 * Константа с FTP путём к отсканированным чекам
*/
define('SCAN_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/scan'); 

/**
 * Константа с путём к размещённым чекам
*/
define('CHECK_DIR', $_SERVER['DOCUMENT_ROOT'].'/data/check');

/**
 * Статусы чеков
*/
$status = array(0=>'Новый', 1=>'Внесенный', 2=>'Ошибочный');

/**
 * Сканирование чеков, запись в базу и перемещение картинок
 *
 * @return boolean статус выполнения
*/
function createCheckPack() {
	if( $handle = opendir(SCAN_DIR) ) {
		if( !is_dir(CHECK_DIR) ) mkdir(CHECK_DIR,0744,true);
		
		while( false !== ($item = readdir($handle)) ) {
			if( !is_dir(SCAN_DIR.'/'.$item) && $item != '.' && $item != '..' ) {
				$imgext = strrchr($item,'.');
				my_query("INSERT INTO `check` SET `imgext`='".$imgext."', `status`=0, `updated`='".date("Y-m-d")."', `created`='".date("Y-m-d")."'");
				$id = mysql_insert_id();
				if( copy(SCAN_DIR.'/'.$item, CHECK_DIR.'/'.$id.$imgext) ) {
					unlink(SCAN_DIR.'/'.$item);
				}
			}
		}
		closedir($handle);
		return true;
	}
	return false;
}

/**
 * Вывод чеков для связывания их с базой подписчиков и журнала + доп. поля
 *
 * @r mysql resource
 * @return void
*/
function showCheckPack($r) {
	if( $r && mysql_num_rows($r) ) {
		$li = '';
		$dir = str_replace($_SERVER['DOCUMENT_ROOT'],'',CHECK_DIR);
		while( $o = mysql_fetch_assoc($r) ) {
			$imgname = $o['id'].$o['imgext'];
			$li .= 
				'<li>'.
					'<a href="'.$dir.'/'.$imgname.'" target="_blank" style="background-image:url('.$dir.'/'.$imgname.')"></a>'.
					'<form method="post" action="/" enctype="multipart/form-data">'.
						'<input type="hidden" name="id" value="'.$o['id'].'">'.
						'<label><b>Подписчик:</b><input type="text" required name="subscriber" class="search" placeholder="Начните вводить ФИО" /></label>'.
						'<label><b>Журнал:</b><input type="text" required name="magazine" class="search" placeholder="Начните вводить название" /></label>'.
						'<label><b>Номер чека:</b><input type="text" required pattern="\d+" name="number" /></label>'.
						'<label><b>Дата отправки:</b><input type="text" required name="sent_date" value="'.date('Y.m.d').'" placeholder="'.date('Y.m.d').'" /></label>'.
						'<label><b>Номер отслеживания:</b><input type="text" required pattern="\d+" name="track" /></label>'.
						'<input type="submit" value="Связать" />'.
					'</form>'.
				'</li>';
		}
		closedir($handle);
		if( !empty($li) ) {
			echo '<h1>Последний пакет отсканированных чеков</h1>';
			echo '<ul class="check-list" id="check-list">'.$li.'</ul>';
		}
	}
}

/**
 * Фильтр для отчёта 1
 *
 * @ot string дата начала
 * @do string дата окончания
 * @return void
*/
function showFilterReport1($ot='',$do='') {
	echo '<form method="get">'.
		'<input type="hidden" name="report">'.
		'Дата от '.
		'<input type="text" name="ot" value="'.$ot.'">'.
		' до '.
		'<input type="text" name="do" value="'.$do.'">'.
		' '.
		'<input type="submit" value="Найти"> '.
		'<a href="/?report">Сброс</a>'.
		'</form>';
}

/**
 * Вывод отчёта 1
 *
 * @r mysql resource
 * @return void
*/
function showReport1($r) {
	if( $r && mysql_num_rows($r) > 0 ) {
		$report = '';
		while( $o = mysql_fetch_assoc($r) ) {
			$s = mysql_fetch_assoc(my_query('SELECT COUNT(*) `cnt_check` FROM `check`WHERE `created`=`updated` && `updated`="'.$o['created'].'" && `status`=1 GROUP BY `updated`'));
			$p = mysql_fetch_assoc(my_query('SELECT COUNT(*) `cnt_check` FROM `check`WHERE `updated`="'.$o['created'].'" && `created`<"'.$o['created'].'" && `status`=1 GROUP BY `updated`'));
			$n = mysql_fetch_assoc(my_query('SELECT COUNT(*) `cnt_check` FROM `check`WHERE `created`="'.$o['created'].'" && `status`=0 GROUP BY `created`'));
			
			$report .= 
				'<tr>'.
					'<td>'.$o['created'].'</td>'.
					'<td>'.(int)$o['cnt_check'].'</td>'.
					'<td>'.(int)$s['cnt_check'].'</td>'.
					'<td>'.(int)$p['cnt_check'].'</td>'.
					'<td>'.(int)$n['cnt_check'].'</td>'.
				'</tr>';
		}
		
		echo 
			'<table border=1 cellpadding=10>'.
				'<tr>'.
					'<td>Дата</td>'.
					'<td>Поступило чеков</td>'.
					'<td>Обработано чеков из текущего дня</td>'.
					'<td>Обработано чеков из предыдущих дней</td>'.
					'<td>Не обработано чеков из текущего дня</td>'.
				'</tr>'.
				$report.
			'</table>';
	}
}