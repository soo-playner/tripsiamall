<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/blocksdk.lib.php');
include_once(G5_LIB_PATH.'/crypto.lib.php');

$wallet_addr = $_POST['wallet_address'];
$wallet_key = $_POST['wallet_key'];
$client = Crypto::GetClient('eth');
$trans_fee = $client-> getBlockChain();

for($i = 0; $i < count($_POST['paid_BTC']); $i++){

    if($_POST['t_status'][$i] == "1"){
        continue;
    }

    if($_POST['coin'][$i] == "eth"){
        $coin = "mb_eth_calc";

        echo "from" . $_POST['wallet_address']."<br>";
        echo "to" . $_POST['addr'][$i]."<br>";
        echo  "amount" . $_POST['amt'][$i]."<br>";
        echo  "private_key" .$_POST['wallet_key']."<br>";
        echo  "gwei" . $trans_fee['high_gwei']."<br>";
        echo  "gas_limit". 21000;

        // $tx = $client->sendToAddress([
        //     "from" => $_POST['wallet_address'],
        //     "to" => $_POST['addr'][$i],
        //     "amount" => $_POST['amt'][$i],
        //     "private_key" =>$_POST['wallet_key'],
        //     "gwei" => $trans_fee['high_gwei'],
        //     "gas_limit" => 21000
        // ]);


    }

    if($_POST['coin'][$i]  == "mbm"){
        $coin = "mb_deposit_calc";

    }


    if($tx['hash']){

    $sql = "UPDATE g5_member SET {$coin} = {$coin} - {$_POST['out_amt'][$i]} WHERE mb_id = '{$_POST['mb_id'][$i]}'";
    // sql_query($sql);

    $sql_2 = "UPDATE wallet_withdrawal_request SET status = '1' WHERE uid = '{$_POST['paid_BTC'][$i]}' ";
    // sql_query($sql_2);
    }
    
    echo $_POST['paid_BTC'][$i]
    ."/".$_POST['mb_id'][$i]
    ."/".$_POST['addr'][$i]
    ."/".$_POST['coin'][$i]
    ."/".$_POST['amt'][$i]
    ."/".$_POST['out_amt'][$i]
    ."/".$_POST['t_status'][$i]."<br>";
}
?>