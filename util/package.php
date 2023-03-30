<?
include_once(G5_THEME_PATH.'/_include/wallet.php');

$shop_item = get_shop_item();
$item_default = substr($shop_item[0]['it_maker'],0,1);
$shop_item_cnt = count($shop_item);

    function package_have_return($mb_id,$have=0){
        
        global $shop_item_cnt,$item_default;
        
        $my_package = [];

        if($have==1){
            $where  = "AND promote = 1 ";
        }else if($have==0){
            $where  = "AND promote != 1 ";
        }

        for($i=0;$i < $shop_item_cnt; $i++ ){
            $target = "package_".strtolower($item_default).$i;
            $sql_r = "SELECT count(*) as cnt from {$target} WHERE mb_id = '{$mb_id}' ".$where;
            $result = sql_fetch($sql_r)['cnt'];
            array_push($my_package,$result);
        }
        return $my_package;
    }

    function my_total_package($mb_id){
        global $item_default,$shop_item_cnt;
        
        $package_head_sql = "SELECT sum(total) as total FROM (";
        $package_sql='';

        for($i=1;$i <= $shop_item_cnt; $i++ ){
            $target = "package_".$item_default.$i;
            $package_sql .= " SELECT COUNT(*) AS total FROM {$target} WHERE mb_id = '{$mb_id}' AND promote != 1 "; 
            if($i < $shop_item_cnt){
                $package_sql .="union all ";
            }
        }
        
        $total_package_sql = $package_head_sql.$package_sql.") tb";
        $total = sql_fetch($total_package_sql)['total'];

        if($total < 1){$total = '-';}
        return $total;
    }
