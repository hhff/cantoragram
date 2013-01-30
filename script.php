<?php
		$font = 'fonts/nevis.ttf';		
		$url="https://api.instagram.com/v1/tags/cantoragram/media/recent?client_id=12af2cf92b114fefa5de229ec5135bf3";
		$jsonContent=file_get_contents($url);
		$content = json_decode($jsonContent);
		$ppUrl = $content->data[0]->user->profile_picture;
		$picUrl = $content->data[0]->images->standard_resolution->url;
		$username = $content->data[0]->user->username;
		$unixTime = $content->data[0]->caption->created_time;
		$newTimeStamp = $content->data[0]->caption->created_time;

//check if there are any pics tagged	
if ($picUrl) {
		//echo "There is a pic tagged hughughfrancisfrancis";

		//create main pic from URL
		$newPic=imagecreatefromstring(file_get_contents($picUrl));			
		
		//Pull in old Timestamp
		$oldTimeStampFileRead = fopen("oldTimeStamp.txt", 'r');
		$oldTimeStamp = fread($oldTimeStampFileRead, 10);
		fclose($oldTimeStampFileRead);
		//echo $oldTimeStamp;
		
		if ($newTimeStamp != $oldTimeStamp) {
			//echo "There is a new pic";
			
		//refresh Timestamp file
		$oldTimeStampFileWrite = fopen("oldTimeStamp.txt", 'w');
		fwrite($oldTimeStampFileWrite, $newTimeStamp);
		fclose($oldTimeStampFileWrite);

		//resize ppURL
		
		$ppUrlImage=imagecreatefromstring(file_get_contents($ppUrl));
		$resize_image = imagecreatetruecolor(45, 45);
		imagecopyresampled($resize_image, $ppUrlImage, 0, 0, 0, 0, 45, 45, 150, 150);
		$ppUrlFinal = $resize_image;
		
		//date created
		$regularTime=date('Y-m-d', $unixTime);			
		
		////////CREATE POLAROID////////
		$frame = imagecreatefromstring(file_get_contents("images/frame.jpg"));		
		
		//load in clock assest
		$clock = imagecreatefromstring(file_get_contents("images/clock.jpg"));
		
		//insert profile pic
		imagecopymerge($frame, $ppUrlFinal, 20, 25, 0, 0, 45, 45, 100);
		
		//insert main pic	
		$resizeMainImage = imagecreatetruecolor(400, 400);
		imagecopyresampled($resizeMainImage, $newPic, 0, 0, 0, 0, 400, 400, 612, 612);
		imagecopymerge($frame, $resizeMainImage, 20, 85, 0, 0, 400, 400, 100); 
		
		//insert username			
		$username_img = imagecreatetruecolor(200, 30);				
		$white_username = imagecolorallocate($username_img, 255, 255, 255);
		$blue_username = imagecolorallocate($username_img, 52, 96, 136);
		imagefilledrectangle($username_img, 0, 0, 200, 30, $white_username);
		imagettftext($username_img, 11, 0, 2, 20, $blue_username, $font, $username); // Add the text		
		imagecopymerge($frame, $username_img, 75, 33, 0, 0, 200, 25, 100);		
		
		//insert time stamp
		$time_img=imagecreatetruecolor(80, 30);
		$white_timestamp = imagecolorallocate($username_img, 255, 255, 255);
		$grey_timestamp = imagecolorallocate($username_img, 83, 83, 83);
		imagefilledrectangle($time_img, 0, 0, 80, 30, $white_timestamp);
		imagettftext($time_img, 10, 0, 2, 20, $grey_timestamp, $font, $regularTime); // Add the text		
		imagecopymerge($frame, $time_img, 345, 33, 0, 0, 80, 30, 100);	
		
		//insert clock pic
		imagecopymerge($frame, $clock, 320, 40, 0, 0, 16, 16, 100);
			

		//insert footer
		$footer_pic=imagecreatefromjpeg("images/footer.jpg");
		imagecopymerge($frame, $footer_pic, 20, 500, 0, 0, 400, 75, 100);		
		
		//count images in directory
		
		$directory = "finished_pics/";
		if (glob($directory . "*.jpg") != false)
		{
		 $filecount = count(glob($directory . "*.jpg"));
		 //echo $filecount;
		}
		else
		{
		 //echo 0;
		}
		
		/////////SAVE ALL IMAGES & POLAROID////////
		imagejpeg($frame, "finished_pics/final".$filecount.".jpg", 100);
		
		////////PRINT PICTURE////////
		imagejpeg($frame, "printFolder/final".$filecount.".jpg", 100);
		
		//echo $username;
		//echo $ppUrl;
		//echo $picUrl;
		//echo $regularTime;
		echo "<meta http-equiv='refresh' content='5;http://localhost:8888/script.php'>";
		
	}else{
		//echo "Last pic in line has already been printed.";
		echo "<meta http-equiv='refresh' content='5;http://localhost:8888/script.php'>";
	}
				
}else{
	
//echo "There are no pics tagged hughhughfrancisfrancis";

// Refresh code
echo "<meta http-equiv='refresh' content='5;http://localhost:8888/script.php'>";

}

?>

<?php 
echo "
<!DOCTYPE html>
	
	<head>
	
	<title>#CantoraGram</title>
	
	<style>
	
	body {
		background-color: #262626;
		text-align:center;
	}
	
	</style>
	
	</head>
	
	<body>
	
		<img src=\"images/welcomescreen.png\" height=\"600\" />
	</body>
</html>"

?>

	
	
	
	
	
	
	