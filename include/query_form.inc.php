<?php
function formCheckbox($name, $desc, $info = '', $defaultChecked = false) {
	global $var;
	if(!empty($info))
		echo '<span class="info">';
	echo "<input type='checkbox' name='$name' id='$name'";
	if(!empty($var[$name]) ||
			(empty($_POST['send']) && $defaultChecked == true))
			echo ' checked';
	echo '>';
	echo "<label for='$name'>$desc</label>";
	if(!empty($info))
		echo '<span class="tooltip">'.$info.'</span></span>';
}

function formInputText($name) {
	global $var;
	$value = empty($var[$name]) ? '' : stripslashes($var[$name]);
	echo "<input type='text' name='$name' size='25' value='$value'>";
}

function formRadioButton($s) {
	global $var;
	$c = &$var["radio_$s"];
	if(!$c)
		$c = 'OR';
	$type = array('AND', 'OR');
	foreach ($type as $t) {
		echo "<input type='radio' name='radio_$s' id='r$s$t' value='$t'".
			($c == $t ? ' checked' : '')."><label for='r$s$t'>$t</label>";
	}
}

// 造出選擇輸出欄位的 select
function formOutColSelect($display = 1, $size = 3) {
	global $var, $AllFields, $DefaultSelection, $SelectedFields;
	if(empty($var['outcol_sel']) ||
		(isset($_POST['SubmitType']) && $_POST['SubmitType'] == '清除儲存')) {
		$SelectedFields = $DefaultSelection;
	} else {
		$SelectedFields = $var['outcol_sel'];
	}

	if(!$display)
		return;
	echo '<select name="outcol_sel[]" multiple size="'.$size.'">';
	foreach($AllFields as $en => $ch) {
		echo '<option value="' . $en . '"' .
			(in_array($en, $SelectedFields) ? ' selected':'') . '>' .
			preg_replace('/<br>/','',$ch);
	}
	echo '</select>';
}

// 預先處理院系代碼, 去除重複以及不存在的, 以過去年度交集為準
$dpt_code_array = explode(' ',
'0000 T010 S010 0050 1 2 3 4 5 6 7 8 9 A B F ' .

// 專班
'J000 J100 J110 ' .

// 大學部
'1010 1020 1030 1040 1050 1060 1070 1080 1090 1011 ' .
'2010 2020 2030 2040 2050 2051 2052 2060 2070 2080 2090 '.
'3010 3020 3021 3022 3023 3030 3050 3051 3052 3100 '.
'4010 4020 4030 4040 4060 4080 4090 4200 '.
'5010 5020 5040 5050 5070 '.
'6010 6020 6030 6031 6032 6040 6050 6051 6052 6053 6054 6060 6070 '.
'6080 6090 6100 6101 6102 6110 6120 6130 '.
'7010 7011 7012 7020 7030 7040 7050 7060 '.
'8010 9010 9020 A010 A011 A012 A013 '.
'B010 B020 '.

//學程
'P P010 P020 P030 P040 P050 P060 P070 P080 P090 P100 P110 '.
'P120 P130 P140 P150 P160 P170 P180 P190 P200 P210 P220 P230 '.
'P240 P250 P260 P270 P280 P290 P300 P310 P320 '.
'P330 P340 P350 P360 P370 P380 P390 P400 P420 '.
'P430 P440 P450 P460 P470 P480 '.

// 研究所
'1M 2M 3M 4M 5M 6M 7M 8M 9M AM BM '.
'1210 1220 1230 1240 1250 1260 1270 1290 1410 1420 1440 1450 '.
'1460 1470 '.
'2210 2220 2230 2240 2241 2242 2250 2260 2270 2271 2272 '.
'2280 2290 2410 2411 2412 2413 2414 2420 2430 2440 2450 2460 '.
'3220 3230 3250 3300 3410 3420 '.
'4210 4220 4230 4240 4260 4280 4290 4410 4420 4430 4440 4450 '.
'4451 4452 4460 4470 4480 4490 4500 4510 4520 '.
'4530 4540 4550 4560 4200 '.
'5210 5211 5212 5213 5215 5216 5217 5220 5221 5223 5224 5225 5226 '.
'5227 5228 5240 5250 5270 5410 5430 5440 5460 5480 5490 '.
'6210 6211 6212 6220 6230 6234 6235 6236 6237 6238 6250 6260 6270 '.
'6280 6281 6282 6283 6290 6300 6310 6320 6330 6410 '.
'6420 6430 6440 6450 '.
'7220 7230 7240 7250 7400 7410 7420 7430 7440 7450 7460 7470 7490 '.
'8410 8420 8430 8440 8450 8451 8452 8460 8470 8480 8490 '.
'9210 9220 9410 9420 9430 9440 9450 A210 A220 A410 '.
'B210 B220 B410 B420 B430 B440 B450 B460 B470 B471 B472 B473 B474');

function fixDptInput() {
global $var, $dpt_choice_filtered, $dpt_code_array;

	if(!empty($var['dpt_choice'])) {
		$dpt_choice_filtered = array_intersect(explode(',',$var['dpt_choice']), $dpt_code_array);
		$dpt_choice_filtered = array_unique($dpt_choice_filtered);
		sort($dpt_choice_filtered);
	}
	if($dpt_choice_filtered)
		echo " value=\"".$dpt_display=implode(",", $dpt_choice_filtered)."\"";
}

// 下拉式的單選選單
function formSelect($name, $array) {
	global $var;
	echo "<select name='$name' id='$name'>";
	if(empty($var[$name]) || !in_array($var[$name], array_keys($array)))
		$var[$name] = '';
	foreach($array as $f => $desc)
		echo "<option value='$f'" . ($var[$name] == $f ? ' selected':'') . '>' . $desc;
	echo '</select>';
}

function displayPager() {
	global $var, $total_count, $display_count;
	echo '<p style="text-align: center;">';
	if($var['start'] != 1)
		echo '<input type="button" class="button" value="上一頁" onClick="document.ThisForm.start.value ='.($var['start']-$var['number']).'; document.ThisForm.SubmitType[0].click();">';
	if($total_count > $var['start'] + $display_count - 1)
		echo ' <input type="button" class="button" value="下一頁" onClick="document.ThisForm.start.value ='.($var['start']+$var['number']).'; document.ThisForm.SubmitType[0].click();">';
	echo '</p>';
}
?>
