<?php
$sub_menu = '100630';

include_once('./_common.php');
include_once(G5_LIB_PATH.'/fcm_push/push.php');

setPushData(
    "제타바이트 📊 내마이닝 해시: 150 mh/s",
    "총보너스 해시 : 2176.58 mh/s (📈12.36 mh/s)   금일 마이닝 총수량 : 0.00842682 ". strtoupper($minings[$now_mining_coin]),
    "cnTcp-v5YTw:APA91bFgQ4aeX9pFF6TTLqU_R345YiHGNb8jXzuH_fa1ehqudbHhbFdU3cTdHzszxZAiKpkgOOpMmFy4P7cYjssnbdwh8vkrhYo4ACnvJpaXZlkfCiIH5pC9QzIkt-ru2ccb-6kq9bZW"
    
);


/* setPushData(
    "제타바이트 📊 내마이닝 해시: 150 mh/s",
    "총보너스 해시 : 2176.58 mh/s (📈12) 금일 마이닝 총수량 : 0.00842682 ETH ",
    "ewwGJ4O255s:APA91bGhry4oQDVAXHbjNlaloLT8OwcbI7cQTJXNmZ_tBBVCkrHeO_FFTtQtH7ewAUT2GYPHeCZpuSUfnR49luX1zg4oKM4xeClMW3aq0Ku8hqHgodkEd36C7morFF7grqxEArAG32IG",
    "https://zetabyte.ai/img/logo.jpg"
); */

?>