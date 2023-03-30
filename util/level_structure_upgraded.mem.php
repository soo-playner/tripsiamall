<?php
include_once('./_common.php');

$mb_no = $_GET['mb_no'];

$sql = "select  mb_no,
			c.mb_id,
			ifnull(mb_recommend_no, 0) as mb_recommend_no,
			mb_recommend,
			mb_email,
			depth,
			mb_open_date as enrolled,
			mb_recommend as sponsor,
			mb_brecommend as placement,
			substring(mb_bre_time,1,10) as placed_binary,
			mb_level,
			pool.it_pool1,
			pool.it_pool2,
			pool.it_pool3,
			pool.it_pool4,
			pool.it_pool5,
			pool.it_gpu
	from	g5_member c 
		left outer join (
			select 
				a.mb_id,
				max(if(it_id = 1527096045, od_receipt_time, 0)) as it_pool1,
				max(if(it_id = 1527096041, od_receipt_time, 0)) as it_pool2,
				max(if(it_id = 1527096037, od_receipt_time, 0)) as it_pool3,
				max(if(it_id = 1527096030, od_receipt_time, 0)) as it_pool4,
				max(if(it_id = 1526013457, od_receipt_time, 0)) as it_pool5,
				max(if(it_id = 1515148167, od_receipt_time, 0)) as it_gpu
			from g5_member c 
			inner join g5_shop_order a on c.mb_id = a.mb_id
			inner join g5_shop_cart b on a.od_id = b.od_id
				where c.mb_no = '{$mb_no}' and a.od_status in ('강제입금', '입금')
			and it_id in (1527096045,1527096041,1527096037,1527096030,1526013457,1515148167)
			group by a.mb_id
		) pool on pool.mb_id = c.mb_id
	where c.mb_no = '{$mb_no}'";

header('Content-Type: application/json');

//echo $sql;
$sth = sql_fetch($sql);
print json_encode($sth);

?>