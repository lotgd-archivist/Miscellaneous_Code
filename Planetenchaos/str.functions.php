
<?php

function strrpos_c($str,$str2,$str3=0,$set="UTF-8"){

return mb_strrpos($str,$str2,$str3,$set);

}

function strpos_c($str,$str2,$str3=0,$set="UTF-8"){

return mb_strpos($str,$str2,$str3,$set);

}

function strlen_c($str,$set="UTF-8"){

return mb_strlen($str,$set);

}

function substr_c($str,$str2,$str3=700000,$set="UTF-8"){

return mb_substr($str,$str2,$str3,$set);

}



function strtolower_c($str,$set="UTF-8"){

return mb_strtolower($str,$set);

}

function strtoupper_c($str,$set="UTF-8"){

return mb_strtoupper($str,$set);

}





function strstr_c($str,$str2,$str3=false,$set="UTF-8"){

return mb_strstr($str,$str2,$str3,$set);

}

function str_replace_c($str,$str2,$str3,$str4=NULL){

if (!is_array($str)) $str = utf8_decode($str);

if (!is_array($str2))$str2 = utf8_decode($str2);

if (!is_array($str3))$str3 = utf8_decode($str3);



if (!is_array($str3)){

 return utf8_encode(str_replace($str,$str2,$str3,$str4));

 }else{

 return str_replace($str,$str2,$str3,$str4);

 }

}





function preg_replace_c($str,$str2,$str3,$str4=-1,$str5=NULL){

if (!is_array($str)) $str = utf8_decode($str);

if (!is_array($str2))$str2 = utf8_decode($str2);

if (!is_array($str3))$str3 = utf8_decode($str3);

if (!is_array($str3)){

 return utf8_encode(preg_replace($str,$str2,$str3,$str4,$str5));

 }else{

  return preg_replace($str,$str2,$str3,$str4,$str5);

 }

}

function substr_count_c($str,$str2,$set="UTF-8"){

//,$str3=0,$str4=NULL

//$str = utf8_decode($str);

//$str2 = utf8_decode($str2);

//return substr_count($str,$str2,$str3,$str4);

return mb_substr_count($str,$str2);

}



function str_word_count_c($str,$str2=0,$str3=""){

$str = utf8_decode($str);

$str3 = utf8_decode($str3);

return str_word_count($str,$str2,$str3);

}



function strncmp_c($str,$str2,$str3=0){

$str = utf8_decode($str);

$str2 = utf8_decode($str2);



return strncmp($str,$str2,$str3);

}

?>

