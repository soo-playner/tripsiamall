<?php
    if(strpos($_SERVER['HTTP_USER_AGENT'],'webview//1.0') !== false){

    echo "<script>

    App.setPrivateKey('$private_key','$mb_id');

    function setPrivateKeyResult(param){
        if(param == 'OK'){

            $.ajax({
                type : 'POST',
                url : './lib/download_key/set_private_key_DB.php',
                dataType : 'json',
                data : {
                    func : 'push',
                    mb_id : '$mb_id'
                }
            })

        }
    }

    </script>";

    }
?>
