<?php
include_once("./Classes/PHPExcel.php");
include_once("./db_connect.php");

$objPHPExcel = new PHPExcel();

$fr_date = $_GET['start_dt'];
$to_date = $_GET['end_dt'];
if(isset($_GET['allowance_chk1'])){$direct = $_GET['allowance_chk1'];}else{$direct = "";}
if(isset($_GET['allowance_chk2'])){$rollup = $_GET['allowance_chk2'];}else{$rollup = "";}
if(isset($_GET['allowance_chk3'])){$cycle = $_GET['allowance_chk3'];}else{$cycle = "";}
if(isset($_GET['stx'])){$mb_id = $_GET['stx'];}else{$mb_id = "";}

$new_fr_date = date_create($fr_date);
$new_to_date = date_create($to_date);

$inerval = date_diff($new_fr_date,$new_to_date);

if($fr_date == "" || $to_date == ""){
    echo 
    "<script>
    alert('기간별 검색에 날짜를 지정, 검색 후 이용해주세요');
    history.back();
    </script>";
    return;
}

// if($inerval->format('%a') > 0){
//     echo 
//     "<script>
//     alert('기간별 검색 설정을 하루 단위로 다시 설정해주세요');
//     history.back();
//     </script>";
//     return;
// }

 $sql_search = "and day >= '$fr_date' and day <= '$to_date'";
 $sql_allow = "";
 $sql_mb_id = "";
 if($mb_id !=""){
    $sql_mb_id .= "and mb_id = '{$mb_id}'";
 }

 if($direct != "" || $rollup != "" || $cycle != ""){
    $sql_allow .= "and (";

    if($direct != ""){

        $sql_allow .= "(allowance_name='$direct')";       

    }
    
    if($rollup != ""){

        if($direct != ""){
            $sql_allow .= " or ( allowance_name='$rollup' )";
        }else{
            $sql_allow .= "( allowance_name='$rollup' )";
        }

    }
    
    if($cycle != ""){

        if($direct != "" || $rollup != ""){
            $sql_allow .= " or ( allowance_name='$cycle' ) ";
        }else{
            $sql_allow .= "( allowance_name='$cycle' ) ";
        }

    }
    $sql_allow .= ")";

 }

$sql = "select * from soodang_pay where (1) {$sql_search} {$sql_mb_id} {$sql_allow} order by day,no desc";

$result = mysqli_query($conn,$sql);

// $row_count = mysqli_num_rows($result);

// if($row_count >= 5000){
// 	echo "<script>
// 	alert('5000건 이상은 다운로드가 되지 않습니다.')
// 	history.back();
// 	</script>";
// 	return;
// }

$arr = array();

for($i=0; $i < $row=mysqli_fetch_array($result); $i++){
  array_push($arr, array("day"=>$row['day'], "id" => $row['mb_id'], "grade" => $row['grade'], "origin_deposit" => $row['origin_deposit'], "allowance_name"=>$row['allowance_name'],
                        "benefit"=> $row['benefit'], "rec"=>$row['rec'],"rec_adm"=>$row['rec_adm']));
}

$objPHPExcel -> setActiveSheetIndex(0)

-> setCellValue("A1", "NO.")

-> setCellValue("B1", "수당날짜")

-> setCellValue("C1", "회원아이디")

-> setCellValue("D1", "회원등급")

-> setCellValue("E1", "현재예치금 (ETH)")

-> setCellValue("F1", "수당이름")

-> setCellValue("G1", "발생수당 (ETH)")

-> setCellValue("H1", "수당근거");


$count = 1;

foreach($arr as $key => $val) {

    $num = 2+$key;
 
	$objPHPExcel -> setActiveSheetIndex(0)

	-> setCellValue(sprintf("A%s", $num), $key+1)

	-> setCellValue(sprintf("B%s", $num), $val['day'])

	-> setCellValue(sprintf("C%s", $num), $val['id'])

    -> setCellValue(sprintf("D%s", $num), $val['grade'])
    
    -> setCellValue(sprintf("E%s", $num), $val['origin_deposit'])

	-> setCellValue(sprintf("F%s", $num), $val['allowance_name'])

	-> setCellValue(sprintf("G%s", $num), Number_format($val['benefit'],2))
	  
	-> setCellValue(sprintf("H%s", $num), $val['rec']." [".$val['rec_adm']." ]");

	  $count ++;

}



// 가로 넓이 조정

$objPHPExcel -> getActiveSheet() -> getColumnDimension("A") -> setWidth(6);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("B") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("C") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("D") -> setWidth(15);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("E") -> setWidth(20);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("F") -> setWidth(25);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("G") -> setWidth(25);

$objPHPExcel -> getActiveSheet() -> getColumnDimension("H") -> setWidth(150);



// 전체 세로 높이 조정

$objPHPExcel -> getActiveSheet() -> getDefaultRowDimension() -> setRowHeight(15);



// 전체 가운데 정렬

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:H%s", $count)) -> getAlignment()

-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



// 전체 테두리 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A1:H%s", $count)) -> getBorders() -> getAllBorders()

-> setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);



// 타이틀 부분

$objPHPExcel -> getActiveSheet() -> getStyle("A1:H1") -> getFont() -> setBold(true);

$objPHPExcel -> getActiveSheet() -> getStyle("A1:H1") -> getFill() -> setFillType(PHPExcel_Style_Fill::FILL_SOLID)

-> getStartColor() -> setRGB("CECBCA");



// 내용 지정

$objPHPExcel -> getActiveSheet() -> getStyle(sprintf("A2:H%s", $count)) -> getFill()

-> setFillType(PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB("F4F4F4");



// 시트 네임

$objPHPExcel -> getActiveSheet() -> setTitle("USER_INFOMATION");



// 첫번째 시트(Sheet)로 열리게 설정

$objPHPExcel -> setActiveSheetIndex(0);



// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.

$filename = iconv("UTF-8", "EUC-KR", "soodang");



// 브라우저로 엑셀파일을 리다이렉션

header("Content-Type:application/vnd.ms-excel");

header("Content-Disposition: attachment;filename=".$filename.".xls");

header("Cache-Control:max-age=0");



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");

$objWriter -> save("php://output");

?>
