<?php
include_once("./Classes/PHPExcel.php");
include_once("../common.php");
$objPHPExcel = new PHPExcel();

$sql = "select * from g5_member";

$result = sql_query($sql);

//엑셀 카테고리 작성
$objPHPExcel -> setActiveSheetIndex(0)
-> setCellValue("A1", "아이디")
-> setCellValue("B1", "메일주소")
-> setCellValue("C1", "EOS(TOTAL)")
-> setCellValue("D1", "Deposit(총 입금합계)")
-> setCellValue("E1", "Conversion(-총 출금합계)")
-> setCellValue("F1", "UPConversion(-업스테어 전환)")
-> setCellValue("G1", "BENEFIT( 총 발생수당 )")
-> setCellValue("H1", "UPSTAIR( 현재업스테어 )")
-> setCellValue("I1", "수당/업스테어(100%)")
-> setCellValue("J1", "UPSTAIR ACC( 누적 업스테어 )")
-> setCellValue("K1", "회원등급")
-> setCellValue("L1", "후원인")
-> setCellValue("M1", "추천인")
-> setCellValue("N1", "가입일");


for($i=2; $row=sql_fetch_array($result); $i++){

  //EOS에 관한 데이터 38 ~ 61줄까지
  $math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$row['mb_id']."'";
  $result_math = sql_query($math_sql);
  $math_total = sql_fetch_array($result_math);

  $math_percent_sql = "select  sum(mb_balance / mb_deposit_point) *20 as percent from g5_member where mb_id =  '".$row['mb_id']."'";
  $result_math_percent = sql_query($math_percent_sql);
  $math_percent = sql_fetch_array($result_math_percent);


  $EOS_BENEFIT_TOTAL = number_format($row['mb_balance'],5); // 수당
  $EOS_TOTAL =  number_format($math_total['total'],5);  //합계잔고
  $EOS_UPSTAIR = number_format($row['mb_deposit_point'],5); // 매출


  if($EOS_UPSTAIR != 0 ){

    $EOS_OUT = number_format($math_percent['percent'],1);
    $EOS_OUT = $EOS_OUT. " %";

  }else{
    $EOS_OUT = " 0.0 %";
  }

  $EOS_UPSTAIR_ACC = number_format($row['mb_deposit_acc'],5);

  //회원등급 조회
  if($row['mb_level'] == 0){
    $level = "Black";
  }else if($row['mb_level'] == 1){
    $level = "Yellow";
  }else if($row['mb_level'] == 2){
    $level = "Blue";
  }else if($row['mb_level'] == 3){
    $level = "Red";
  }else{
    $level = "Green";
  }


  //엑셀 카테고리들에 대한 데이터
  $objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue("A$i", iconv("utf-8","euc-kr",$row['mb_id'])) //아이디
  ->setCellValue("B$i", iconv("utf-8","euc-kr",$row['mb_email'])) //메일주소
  ->setCellValue("C$i", iconv("utf-8","euc-kr",$EOS_TOTAL)) //EOS(TOTAL)
  ->setCellValue("D$i", iconv("utf-8","euc-kr",number_format($row['mb_save_point'],3))) //Deposit(총 입금합계)
  ->setCellValue("E$i", iconv("utf-8","euc-kr",number_format($row['mb_shift_amt'],5))) //Conversion(-총 출금합계)
  ->setCellValue("F$i", iconv("utf-8","euc-kr",number_format($row['mb_deposit_calc'],5))) //UPConversion(-업스테어 전환)
  ->setCellValue("G$i", iconv("utf-8","euc-kr",$EOS_BENEFIT_TOTAL)) //BENEFIT( 총 발생수당 )
  ->setCellValue("H$i", iconv("utf-8","euc-kr",$EOS_UPSTAIR)) //UPSTAIR( 현재업스테어 )
  ->setCellValue("I$i", iconv("utf-8","euc-kr",$EOS_OUT)) //수당/업스테어(100%)
  ->setCellValue("J$i", iconv("utf-8","euc-kr",$EOS_UPSTAIR_ACC)) //UPSTAIR ACC( 누적 업스테어 )
  ->setCellValue("K$i", iconv("utf-8","euc-kr",$level)) // 회원등급
  ->setCellValue("L$i", iconv("utf-8","euc-kr",$row['mb_brecommend'])) //후원인
  ->setCellValue("M$i", iconv("utf-8","euc-kr",$row['mb_recommend'])) //추천인
  ->setCellValue("N$i", iconv("utf-8","euc-kr",$row['mb_open_date'])); //가입일

}

//엑셀 파일명 및 다운로드
$objPHPExcel->setActiveSheetIndex(0);
$filename = iconv("UTF-8", "EUC-KR", "전체회원엑셀파일");

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>
