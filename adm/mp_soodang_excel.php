<?php
$sub_menu = "600920";
include_once("./_common.php");
include_once(G5_SMS5_PATH.'/sms5.lib.php');
include_once(G5_LIB_PATH.'/PHPExcel-1.8/Classes/PHPExcel.php');

if($_GET['mb_id']){
	$sql_condition .= " and mb_id like '%".$_GET['mb_id']."%'";
	$qstr .= "&mb_id=".$_GET['mb_id'];
}

if($_GET[mp_id]){
	$sql_condition .= " and mb_mprecommend like '%".$_GET[mp_id]."%'";
	$qstr .= "&mb_id=".$_GET[mp_id];
}

if($_GET[start_dt]){
	$sql_condition .= " and DATE_FORMAT(create_dt, '%Y-%m-%d') >= '".$_GET[start_dt]."'";
	$qstr .= "&start_dt=".$_GET[start_dt];
}
if($_GET[end_dt]){
	$sql_condition .= " and DATE_FORMAT(create_dt, '%Y-%m-%d') <= '".$_GET[end_dt]."'";
	$qstr .= "&end_dt=".$_GET[end_dt];
}

$sql = " select count(*) as cnt from mp_soodang WHERE 1=1 ";
$sql .= $sql_condition;
$row = sql_fetch($sql,true);
$total_count = $row['cnt'];

$sql = "select * from mp_soodang WHERE 1=1 ";
$sql .= $sql_condition;
$sql .= " order by create_dt desc ";

$qry = sql_query($sql);

if (!$total_count) alert_just('데이터가 없습니다..', G5_URL."/adm/mp_soodang.php");

// $objPHPExcel = new PHPExcel();

// // Set document properties
// $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
// 							 ->setLastModifiedBy("Maarten Balliauw")
// 							 ->setTitle("Office 2007 XLSX Test Document")
// 							 ->setSubject("Office 2007 XLSX Test Document")
// 							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
// 							 ->setKeywords("office 2007 openxml php")
// 							 ->setCategory("Test result file");


// // Add some data
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A1', 'No')
//             ->setCellValue('B1', '가입일')
//             ->setCellValue('C1', '가입자')
// 			->setCellValue('D1', 'MP')
// 			->setCellValue('E1', '커미션($)')
// 			->setCellValue('F1', '시세(usd/btc)');

// for($i=2; $res=sql_fetch_array($qry); $i++)
// {
// 	// $res = array_map('iconv_euckr', $res);
// 	$objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A'.$i, $res['idx'])
//             ->setCellValue('B'.$i, $res['create_dt'])
//             ->setCellValue('C'.$i, $res['mb_id'])
// 			->setCellValue('D'.$i, $res['mb_mprecommend'])
// 			->setCellValue('E'.$i, $res['commission'])
// 			->setCellValue('F'.$i, $res['usdbtc']);
// }
// // Rename worksheet
// $objPHPExcel->getActiveSheet()->setTitle('mp정산');


function column_char($i) { return chr( 65 + $i ); }
 
// 자료 생성
$headers = array('ID','부서ID','이름','이메일','나이');

$rp=0;
while($res=sql_fetch_array($qry))
{
	$a = "select mb_level from g5_member where mb_id='".$res['mb_id']."'";
	$b = sql_fetch($a);			
	if( $b['mb_level']>=2){
		$commission = $res[commission];
		$exchage = $res['usdbtc'];
	}else{
		$commission = 0;
		$exchage = 0;
	}
	$rows[$rp] = array($res['idx'], $res['create_dt'], $res['mb_id'], $res['mb_mprecommend'], $commission, $exchage);
	$rp = $rp+1;
	echo 'rp'.$rp.'<br>';
}
$data = array_merge(array($headers), $rows);
 
// 스타일 지정
$widths = array(10, 20, 20, 30, 10);
$header_bgcolor = 'FFABCDEF';

// 엑셀 생성
$last_char = column_char( count($headers) - 1 );
 
$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
$excel->getActiveSheet()->fromArray($data,NULL,'A1');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="web-test.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');

?>