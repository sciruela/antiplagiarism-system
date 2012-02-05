<?php
include("webscrapping.php");
if ((($_FILES["fileToUpload"]["type"] == "application/pdf")
|| ($_FILES["fileToUpload"]["type"] == "image/jpeg")|| ($_FILES["fileToUpload"]["type"] == "image/pjpeg")))
  {
  if ($_FILES["fileToUpload"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["fileToUpload"]["error"] . "<br />";
    }
  else
    {
    echo "Upload: " . $_FILES["fileToUpload"]["name"] . "<br />";
    echo "Type: " . $_FILES["fileToUpload"]["type"] . "<br />";
    echo "Size: " . ($_FILES["fileToUpload"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["fileToUpload"]["tmp_name"] . "<br />";

    if (file_exists("upload/" . $_FILES["fileToUpload"]["name"]))
      {
      echo $_FILES["fileToUpload"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],
      "upload/" . $_FILES["fileToUpload"]["name"]);
      echo "Stored in: " . "upload/" . $_FILES["fileToUpload"]["name"]."</br>";
      $content = shell_exec('/usr/local/bin/pdftotext upload/'.$_FILES["fileToUpload"]["name"].' -');
      $counter=0;
      $mystrtok=explode('.',$content);
	for($i=0;$i<count($mystrtok);$i++)
		$counter+=query($mystrtok[$i]);

      }
	echo "Plagiarism Rate ",($counter/(count($mystrtok)-1))*100 ,"%";
    }
  }
else
  {
  echo "Invalid file";
  }
?>