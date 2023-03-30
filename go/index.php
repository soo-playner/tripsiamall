<?php 
include_once('./_common.php');



$servername = G5_MYSQL_HOST;
$username = G5_MYSQL_USER;
$password = G5_MYSQL_PASSWORD; // on localhost by default there is no password
$dbname = G5_MYSQL_DB;

$base = str_replace(array('www.'), '', G5_URL);
$base_url= G5_URL.'/go/'; // it is your application url

$geturl = $_GET['url'];

if( $geturl && $geturl != "" )
{ 
    $url=urldecode($geturl);

    if (filter_var($url, FILTER_VALIDATE_URL)) 
    {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $slug=GetShortUrl($url);
    $conn->close();

    echo $base_url.$slug;
    //echo json_encode( $base_url.$slug);
} 
else 
{
die("$url is not a valid URL");
}

}
else
{
?>



<form style="margin:400px 0;">
<p ><input style="width:500px;height:60px;" type="url" name="url" required value="<?=$_GET['makeurl']?>"/></p>
<p><input type="submit" value="만들기"/></p>
</form>

<?php
}




function GetShortUrl($url){
 global $conn;
 $urlstring = explode('recom_referral=',$url);
 $mb_no = $urlstring[1];
 
 $query = "SELECT * FROM url_shorten WHERE url = '".$url."' "; 

 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
 return $row['short_code'];
} else {
$short_code = generateUniqueID();
$sql = "INSERT INTO url_shorten (url, short_code, hits,mb_no)
VALUES ('".$url."', '".$short_code."', '0','".$mb_no."')";
if ($conn->query($sql) === TRUE) {
return $short_code;
} else { 
die("Unknown Error Occured");
}
}
}



function generateUniqueID(){
 global $conn; 
 $token = substr(md5(uniqid(rand(), true)),0,4); // creates a 6 digit unique short id
 $query = "SELECT * FROM url_shorten WHERE short_code = '".$token."' ";
 $result = $conn->query($query); 
 if ($result->num_rows > 0) {
 generateUniqueID();
 } else {
 return $token;
 }
}


if($_GET['redirect']!="")
{ 
$slug=urldecode($_GET['redirect']);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$url= GetRedirectUrl($slug);
$conn->close();
    header("location:".$url);
exit;
}


function GetRedirectUrl($slug){
 global $conn;
 $query = "SELECT * FROM url_shorten WHERE short_code = '".addslashes($slug)."' "; 
 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
// increase the hit
$hits=$row['hits']+1;
$sql = "update url_shorten set hits='".$hits."' where id='".$row['id']."' ";
$conn->query($sql);
return $row['url'];
}
else 
 { 
die("Invalid Link!");
}
}