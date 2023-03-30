<?php
include_once('/logcompany.php');
include_once("./Classes/PHPExcel.php");
include_once("./db_connect.php");

function package_have_return($mb_id,$have=0){
	global $conn;
	$my_package = [];

	if($have==1){
		$where  = "AND promote = 1 ";
	}else if($have==0){
		$where  = "AND promote != 1 ";
	}

	$sql_r = "SELECT count(*) as cnt from package_m1 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	$sql_r = "SELECT count(*) as cnt from package_m2 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	$sql_r = "SELECT count(*) as cnt from package_m3 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	$sql_r = "SELECT count(*) as cnt from package_m4 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	$sql_r = "SELECT count(*) as cnt from package_m5 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	$sql_r = "SELECT count(*) as cnt from package_m6 WHERE mb_id = '{$mb_id}' ".$where;
	$result = mysqli_query($conn,$sql_r);
	$row = mysqli_fetch_row($result);
	array_push($my_package,$row[0]);

	// if($have==0){

	//     $sql_r = "SELECT count(*) as cnt from package_r3 WHERE mb_id = '{$mb_id}' AND promote = 1";
	//     $result = sql_fetch($sql_r)['cnt'];
	//     array_push($my_package,$result);

	//     $sql_r = "SELECT count(*) as cnt from package_r0 WHERE mb_id = '{$mb_id}' AND it_name REGEXP '^f' ";
	//     $result = sql_fetch($sql_r)['cnt'];
	//     array_push($my_package,$result);

	//     $sql_r = "SELECT count(*) as cnt from package_r WHERE mb_id = '{$mb_id}' ";
	//     $result = sql_fetch($sql_r)['cnt'];
	//     array_push($my_package,$result);

	// }
	return $my_package;
}

$objPHPExcel = new PHPExcel();

$sql = "select * from g5_member order by mb_no asc";

$result = mysqli_query($conn,$sql);

$arr = array();

for($i=0; $i < $row=mysqli_fetch_array($result); $i++){

	$pack_array = package_have_return($row['mb_id']);

  array_push($arr, array("id" => $row['mb_id'], "sponsor"=>$row['mb_sponsor'], "recom"=> $row['mb_recommend'], "mb_brecommend"=>$row['mb_brecommend'],
  "mail" => $row['mb_email'],"eth" => $row['mb_eth_point']+$row['mb_eth_calc'], "mbm" => $row['mb_deposit_point']+ $row['mb_deposit_calc'], "soodang"=>$row['mb_balance'],
  "pv"=>$row['mb_rate'], "m1" => $pack_array[0],"m2" => $pack_array[1],"m3" => $pack_array[2],"m4" => $pack_array[3],"m5" => $pack_array[4],"m6" => $pack_array[5]
));

}


$objPHPExcel -> setActiveSheetIndex(0)

-> setCellValue("A1", "NO.")

-> setCellValue("B1", "회원 아이디")

-> setCellValue("C1", "추천상위스폰서")

-> setCellValue("D1", "추천인")

-> setCellValue("E1", "후원인")

-> setCellValue("F1", "메일주소")

-> setCellValue("G1", $minings[$now_mining_coin])

-> setCellValue("H1", "MBM")

-> setCellValue("I1", "BENEFIT(총 수당)")

-> setCellValue("J1", "PV")

-> setCellValue("K1", "M1")

-> setCellValue("L1", "M2")

-> setCellValue("M1", "M3")

-> setCellValue("N1", "M4")

-> setCellValue("O1", "M5")

-> setCellValue("P1", "M6");


$count = 1;

foreach($arr as $key => $val) {

	$num = 2 + $key;

	$objPHPExcel -> setActiveSheetIndex(0)


	

	-> setCellValue(sprintf("A%s", $num), $key+1)

	-> setCellValue(sprintf("B%s", $num), $val['id'])

	-> setCellValue(sprintf("C%s", $num), $val['sponsor'])

	-> setCellValue(sprintf("D%s", $num), $val['recom'])

	-> setCellValue(sprintf("E%s", $num), $val['mb_brecommend'])

	-> setCellValue(sprintf("F%s", $num), $val['mail'])
	  
	-> setCellValue(sprintf("G%s", $num), $val['eth'])

	-> setCellValue(sprintf("H%s", $num),$val['mbm'])

	-> setCellValue(sprintf("I%s", $num),$val['soodang'])

	-> setCellValue(sprintf("J%s", $num),$val['pv'])

	-> setCellValue(sprintf("K%s", $num),$val['m1'])

	-> setCellValue(sprintf("L%s", $num),$val['m2'])

	-> setCellValue(sprintf("M%s", $num),$val['m3'])

	-> setCellValue(sprintf("N%s", $num),$val['m4'])

	-> setCellValue(sprintf("O%s", $num),$val['m5'])

	-> setCellValue(sprintf("P%s", $num),$val['m6']);

	$count++;

}



// 가로 넓이 조정

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(6);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(30);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("I") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("J") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("K") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("L") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("M") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("N") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("O") -> setWidth(17);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("P") -> setWidth(17);



// 전체 세로 높이 조정

$objPHPExcel -> getActiveSheet() -> getDefaultRowDimension() -> setRowHeight(15);



// 전체 가운데 정렬

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:P%s", $count)) -> getAlignment()

-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



// 전체 테두리 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:P%s", $count)) -> getBorders() -> getAllBorders()

-> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



// 타이틀 부분

$objPHPExcel -> getActiveSheet() -> getStyle("A1:P1") -> getFont() -> setBold(true);

$objPHPExcel -> getActiveSheet() -> getStyle("A1:P1") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID)

-> getStartColor() -> setRGB("CECBCA");



// 내용 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:P%s", $count)) -> getFill()

-> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("F4F4F4");



// 시트 네임

$objPHPExcel -> getActiveSheet() -> setTitle("USER_INFOMATION");



// 첫번째 시트(Sheet)로 열리게 설정

$objPHPExcel -> setActiveSheetIndex(0);



// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.

$filename = iconv("UTF-8", "EUC-KR", "USER_INFO");



// 브라우저로 엑셀파일을 리다이렉션

header("Content-Type:application/vnd.ms-excel");

header("Content-Disposition: attachment;filename=".$filename.".xls");

header("Cache-Control:max-age=0");



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");

$objWriter -> save("php://output");

?>
