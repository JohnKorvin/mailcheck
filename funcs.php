<?

include "config.php";
include "dbwork.php";
include "design.php";

function createCheckPack() {
	if( $handle = opendir(SCAN_DIR) ) {
		if( !is_dir(PACK_DIR) ) mkdir(PACK_DIR,0744,true);
		
		while( false !== ($item = readdir($handle)) ) {
			if( !is_dir(SCAN_DIR.'/'.$item) && $item != '.' && $item != '..' ) {
				if( copy(SCAN_DIR.'/'.$item, PACK_DIR.'/'.$item) ) {
					unlink(SCAN_DIR.'/'.$item);
				}
			}
		}
		closedir($handle);
		return true;
	}
	return false;
}

function showCheckPack() {
	if( $handle = opendir(PACK_DIR) ) {
		$li = '';
		$dir = str_replace($_SERVER['DOCUMENT_ROOT'],'',PACK_DIR);
		while( false !== ($item = readdir($handle)) ) {
			if( !is_dir(PACK_DIR.'/'.$item) && $item != '.' && $item != '..' ) {
				$li .= 
					'<li>'.
						'<a href="'.$dir.'/'.$item.'" target="_blank" style="background-image:url('.$dir.'/'.$item.')"></a>'.
						'<form method="post" action="/" enctype="multipart/form-data">'.
							'<input type="hidden" name="check" value="'.$item.'">'.
							'<label><b>Подписчик:</b><input type="text" required name="subscriber" class="search" placeholder="Начните вводить ФИО" /></label>'.
							'<label><b>Журнал:</b><input type="text" required name="magazine" class="search" placeholder="Начните вводить название" /></label>'.
							'<label><b>Номер чека:</b><input type="text" required name="number" /></label>'.
							'<label><b>Дата отправки:</b><input type="text" required name="sent_date" value="'.date('Y.m.d').'" placeholder="'.date('Y.m.d').'" /></label>'.
							'<label><b>Номер отслеживания:</b><input type="text" required name="track" /></label>'.
							'<input type="submit" value="Связать" />'.
						'</form>'.
					'</li>';
			}
		}
		closedir($handle);
		if( !empty($li) ) {
			echo '<h1>Последний пакет отсканированных чеков</h1>';
			echo '<ul class="check-list">'.$li.'</ul>';
		}
	}
}

function showReport1($r) {
	if( $r && mysql_num_rows($r) > 0 ) {
		$report = '';
		while( $o = mysql_fetch_assoc($r) ) {
			$s = mysql_fetch_assoc(my_query('SELECT COUNT(*) `cnt_check` FROM `check`WHERE `sent_date`="'.$o['created'].'" GROUP BY `created`'));
			$v = mysql_fetch_assoc(my_query('SELECT COUNT(*) `cnt_check` FROM `check`WHERE `sent_date`!="'.$o['created'].'" GROUP BY `created`'));
			
			$report .= 
				'<tr>'.
					'<td>'.$o['created'].'</td>'.
					'<td>'.$o['cnt_check'].'</td>'.
					'<td>'.$s['cnt_check'].'</td>'.
					'<td>'.$v['cnt_check'].'</td>'.
				'</tr>';
		}
		
		echo 
			'<table border=1 cellpadding=10>'.
				'<tr>'.
					'<td>Дата</td>'.
					'<td>Поступило чеков</td>'.
					'<td>Обработано чеков из текущего дня</td>'.
					'<td>обработано чеков из предыдущих дней</td>'.
					'<td>Не обработано чеков из текущего дня</td>'.
				'</tr>'.
				$report.
			'</table>';
	}
}