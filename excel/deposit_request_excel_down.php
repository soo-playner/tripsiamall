<?php
include_once("./Classes/PHPExcel.php");
include_once("./db_connect.php");

$objPHPExcel = new PHPExcel();

$fr_date = $_GET['fr_date'];
$to_date = $_GET['to_date'];
if(isset($_GET['fr_id'])){$fr_id = $_GET['fr_id'];}else{$fr_id = "";}
if(isset($_GET['update_dt'])){$update_dt = $_GET['update_dt'];}else{$update_dt = "";}
if(isset($_GET['status'])){$status = $_GET['status'];}else{$status = "";}
// $new_fr_date = date_create($fr_date);
// $new_to_date = date_create($to_date);

// $inerval = date_diff($new_fr_date,$new_to_date);

// if($fr_date == "" || $to_date == ""){
//     echo 
//     "<script>
//     alert('기간별 검색에 날짜를 지정, 검색 후 이용해주세요');
//     history.back();
//     </script>";
//     return;
// }

// if($inerval->format('%a') > 0){
//     echo 
//     "<script>
//     alert('기간별 검색 설정을 하루 단위로 다시 설정해주세요');
//     history.back();
//     </script>";
//     return;
// }
// A.mb_id = 'test0' 
$sql_fr_id = "";
if($fr_id != ""){
    $sql_fr_id .= "and A.mb_id = '{$fr_id}'";
}

$sql_update_dt = "";
if($update_dt != ""){
    $sql_update_dt .= "and DATE_FORMAT(A.update_dt, '%Y-%m-%d') = '{$update_dt}'";
    
}

$sql_status = "";
if($status != ""){
    $sql_status .= "and A.status = '{$status}'";
}

$sql = "select * from wallet_deposit_request as A WHERE 1=1 {$sql_fr_id} {$sql_update_dt} {$sql_status} and DATE_FORMAT(A.create_dt, '%Y-%m-%d') between '$fr_date' and '$to_date' order by create_dt desc";

$result = mysqli_query($conn,$sql);

$row_count = mysqli_num_rows($result);

if($row_count >= 5000){
	echo "<script>
	alert('5000건 이상은 다운로드가 되지 않습니다.')
	history.back();
	</script>";
	return;
}

$arr = array();

for($i=0; $i < $row=mysqli_fetch_array($result); $i++){

    $sub_sql = "SELECT mb_id, mb_brecommend FROM g5_member WHERE mb_id = (SELECT mb_recommend FROM g5_member WHERE mb_id='{$row['mb_id']}')";
    $sub_result = mysqli_query($conn, $sub_sql);
    $sub_row = mysqli_fetch_array($sub_result);

  array_push($arr, array("no"=>$row['uid'], "id" => $row['mb_id'], "mb_recommend"=>$sub_row['mb_id'],"mb_brecommend"=>$sub_row['mb_brecommend'] ,"txhash" => $row['txhash'], "coin"=>$row['coin'],
                        "in_amt"=> $row['in_amt'], "status"=>$row['status'],"create_dt"=>$row['create_dt'],"update_dt"=>$row['update_dt']));

               
}

$objPHPExcel -> setActiveSheetIndex(0)

-> setCellValue("A1", "NO.")

-> setCellValue("B1", "no")

-> setCellValue("C1", "아이디")

-> setCellValue("D1", "추천인")

-> setCellValue("E1", "추천인의후원인")

-> setCellValue("F1", "Transaction_hash")

-> setCellValue("G1", "코인종류")

-> setCellValue("H1", "입금확인금액(각코인단위)")

-> setCellValue("I1", "승인여부")

-> setCellValue("J1", "요청시간")

-> setCellValue("K1", "상태변경일");


$count = 1;

foreach($arr as $key => $val) {

    $num = 2+$key;

     $status_num = $val['status'];
    
     $status_num == 0 ? $status = "요청" : "";
     $status_num == 1 ? $status = "승인" : "";
     $status_num == 2 ? $status = "대기" : "";
     $status_num == 3 ? $status = "불가" : "";
     $status_num == 4 ? $status = "취소" : "";
 
    $objPHPExcel -> setActiveSheetIndex(0)

	-> setCellValue(sprintf("A%s", $num), $key+1)

	-> setCellValue(sprintf("B%s", $num), $val['no'])

    -> setCellValue(sprintf("C%s", $num), $val['id'])
    
    -> setCellValue(sprintf("D%s", $num), $val['mb_recommend'])

    -> setCellValue(sprintf("E%s", $num), $val['mb_brecommend'])

    -> setCellValue(sprintf("F%s", $num), $val['txhash'])
    
    -> setCellValue(sprintf("G%s", $num), $val['coin'])

	-> setCellValue(sprintf("H%s", $num), $val['in_amt'])

	-> setCellValue(sprintf("I%s", $num), $status)
	  
    -> setCellValue(sprintf("J%s", $num), $val['create_dt'])
    
    -> setCellValue(sprintf("K%s", $num), $val['update_dt']);

	  $count ++;

}



// 가로 넓이 조정

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(6);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(10);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(75);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(15);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(30);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("I") -> setWidth(15);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("J") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("K") -> setWidth(20);



// 전체 세로 높이 조정

$objPHPExcel -> getActiveSheet() -> getDefaultRowDimension() -> setRowHeight(15);



// 전체 가운데 정렬

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:K%s", $count)) -> getAlignment()

-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



// 전체 테두리 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:K%s", $count)) -> getBorders() -> getAllBorders()

-> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



// 타이틀 부분

$objPHPExcel -> getActiveSheet() -> getStyle("A1:K1") -> getFont() -> setBold(true);

$objPHPExcel -> getActiveSheet() -> getStyle("A1:K1") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID)

-> getStartColor() -> setRGB("CECBCA");



// 내용 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:K%s", $count)) -> getFill()

-> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("F4F4F4");



// 시트 네임

$objPHPExcel -> getActiveSheet() -> setTitle("USER_INFOMATION");



// 첫번째 시트(Sheet)로 열리게 설정

$objPHPExcel -> setActiveSheetIndex(0);



// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.

$filename = iconv("UTF-8", "EUC-KR", "depositRequest");



// 브라우저로 엑셀파일을 리다이렉션

header("Content-Type:application/vnd.ms-excel");

header("Content-Disposition: attachment;filename=".$filename.".xls");

header("Cache-Control:max-age=0");



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");

$objWriter -> save("php://output");

?>
