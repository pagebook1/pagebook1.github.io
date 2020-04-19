<?php
 $hostname = "65.19.143.6";
    $username = "pagebook_1";
    $password = "031920Kevin";
    $databaseName = "pagebook_dns";
 // connect to mysql database

$connect = mysqli_connect($hostname, $username, $password, $databaseName);
$query = "SELECT * FROM `domain`";
$result2 = mysqli_query($connect, $query);
$query1 = "SELECT `count` FROM domain";
$count = mysqli_query($connect,$query1);
$delete = "DELETE FROM `domain` WHERE `count` >= 1000";
$deleteto = mysqli_query($connect,$delete);
$options = "";
$text = "";

while($row2 = mysqli_fetch_array($result2))
{
    $options = $options."<option>$row2[1]</option>";
}

if(isset($_POST['submit'])){
    /* Cloudflare.com | APİv4 | Api Ayarları */
    $apikey = '1e0b4b6eed0cac43edc07960181592d541825'; // Cloudflare Global API
    $email = 'pagebook.kevs@gmail.com'; // Cloudflare Email Adress
    $domain = $_POST['domain'];  // zone_name // Cloudflare Domain Name
    // $zoneid = $zoneid2; // zone_id // Cloudflare Domain Zone ID
    $ip = $_POST['ipaddress'];
    $name = $_POST['name'];
    $zoneidquery = mysqli_query($connect,"SELECT `zoneid` FROM `domain` WHERE domain = '$domain'");
    $zoneid1 = mysqli_fetch_array($zoneidquery);
    $zoneid  = $zoneid1[0];



    // A-record oluşturur DNS sistemi için.
    		$ch = curl_init("https://api.cloudflare.com/client/v4/zones/".$zoneid."/dns_records");
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    		'X-Auth-Email: '.$email.'',
    		'X-Auth-Key: '.$apikey.'',
    		'Cache-Control: no-cache',
    	    // 'Content-Type: multipart/form-data; charset=utf-8',
    	    'Content-Type:application/json',
    		'purge_everything: true'
    		
    		));
    	
    		// -d curl parametresi.
    		$data = array(
    		
    			'type' => 'A',
    			'name' => $name,
    			'content' => $ip,
    			'zone_name' => ''.$domain.'',
    			'zone_id' => ''.$zoneid.'',
    			'proxiable' => 'false',
    			'proxied' => false,
    			'ttl' => 1
    		);
    		       
    		
    		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_string));

    		

                 // If you want show output remove code slash.
		          // print_r($sonuc);

    		
            if($name=="*" || $name == $domain || $name == "ftp"|| $name=="www"){
                     
                    $text = "<div class='alert alert-danger'>
                            <strong>Failed!</strong> ".$name.".".$domain." is not Available
                            </div></div></div>";
                    
            }else{
                     $data_string = json_encode($data);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                    $sonuc = curl_exec($ch);
                    curl_close($ch);
                    
                    $text =  "<div class='alert alert-success'>
                    <strong>Success!</strong> ".$name.".".$domain." <strong>Created</strong>
                    </div></div></div>";
                    $insert="UPDATE `domain` SET `count`= `count` + 1 WHERE domain='$domain'";
                    $hostname = "65.19.143.6";
                    $username = "pagebook_1";
                    $password = "031920Kevin";
                    $databaseName = "pagebook_dns";
                    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
                    $execute = mysqli_query($connect, $insert);
                    //echo "<div class='alert alert-success alert-dismissable'><center>Your hostname <b> $name.$domain</b> is now online! </div>";
                    
                    
                }
            }
 // php select option value from database
?>




<html>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> 

<head>
<script src='https://www.google.com/recaptcha/api.js' async defer ></script>
    <title> FASTDNS DNS Maker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    	<script>
window.onload = function() {
  var recaptcha = document.forms["myForm"]["g-recaptcha-response"];
  recaptcha.required = true;
  recaptcha.oninvalid = function(e) {
    // do something
    alert("Please complete the captcha");
  }
}
</script>
<script type="text/javascript">
	$(function(){
  function rescaleCaptcha(){
    var width = $('.g-recaptcha').parent().width();
    var scale;
    if (width < 302) {
      scale = width / 302;
    } else{
      scale = 1.0; 
    }

    $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
    $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
    $('.g-recaptcha').css('transform-origin', '0 0');
    $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
  }

  rescaleCaptcha();
  $( window ).resize(function() { rescaleCaptcha(); });

});
</script>
</head>
<style type="text/css">
    @media screen and (max-width: 375px) {
    .col-4{
        min-width: 95%;
        margin-top: 5px;
    }
}
@media screen and (max-width: 768px) {
    .col-4{
        min-width: 80%;
        margin-top: 5px;
    }
}
@media screen and (max-width: 1440px) {
    .col-4{
        margin-top: 10px;
    }
}
</style>
</style>
<div class="row justify-content-center">
    <div class="col-4">
        <?php echo $text ?>
    </div>
</div>
<div class="row justify-content-center">
<div class="col-4">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="myForm" >
        <div class="form-group">
            <label class="display-4 container " ><center>FASTDNS Clean UI</center></label>
            <br><br>
            <input type="text" class="form-control" placeholder="Domain" name="name" required=""><br>
            <div class="row">
                <div class="col-8">
                    <input type="text" class="form-control" placeholder="127.0.0.1" name="ipaddress" required="">
                </div>
                <div class="col">
                    <select name="domain" class="btn btn-outline-info btn-md m-0 px-3 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $options;?>
                    </select>
                </div>
            </div>
        </div>    
        <div align="center" class="g-recaptcha" data-sitekey="6Lc7xdwUAAAAAAbbSRL2lr36pljlTlvS1ODaRnkT" style="">
								<?php
 
	 								if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
	  								{
	        								$secret = '6Lc7xdwUAAAAAL26JNUsmBxrMWIomuehPezjYFoK';
	        								$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
	        								$responseData = json_decode($verifyResponse);
	        						if($responseData->success)
	        						{
	            							$succMsg = 'Your contact request have submitted successfully.';
	        						}
	        						else
	        						{
	            					$errMsg = 'Robot verification failed, please try again.';
	        						}
	   								}
									?>
								</div><br>
    <center><input type="submit" name="submit" value= "submit" class="btn btn-primary"></center>
</div>
</form> 
</div>
</div>
<div class="row justify-content-center">
    <div class="col-4">
        <div class="alert alert-danger alert-dismissible fade show">
        Please Click The Ads to Support this Website
        </div>
    </div>
</div>
<div class="row justify-content-center">
<iframe data-aa="1365886" src="//ad.a-ads.com/1365886?size=300x250" scrolling="no" style="width:300px; height:250px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
</div>
<div class="row justify-content-center">
    <div class="col-4">
       <div class="alert alert-success alert-dismissible fade show">
        Your IP Address: &nbsp
        <?php echo $_SERVER['REMOTE_ADDR']; ?>
        </div>
    </div>
</div>
</html>
