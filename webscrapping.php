<?php
include('simple_html_dom.php');

function query($sentence){

$sentence=str_replace(' ','+',strtolower($sentence));
$html = file_get_html('https://www.google.com/search?q="'.$sentence.'"');



$pos = strrpos($html->find('div[id=center_col]',0)->plaintext,"did not match any documents");

if($pos===false){
	//echo $html->find('h3[class=r]',0)->children(0)->href;
	return 1;
}else{
return 0;
}
}


?>
