<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 03/10/2017
 * Time: 15:35
 */


$apiKey = '1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e';
$apiSecret = '96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac';

$type_event = !empty($_POST['type_event']) ? $_POST['type_event'] : null;
$custom_field = !empty($_POST['custom_field']) ? json_decode($_POST['custom_field']) : null;
$api_key_sha256 = !empty($_POST['api_key_sha256']) ? $_POST['api_key_sha256'] : null;
$api_secret_sha256 = !empty($_POST['api_secret_sha256']) ? $_POST['api_secret_sha256'] : null;


if(hash('sha256', $apiKey) === $api_key_sha256 && hash('sha256', $apiSecret) === $api_secret_sha256)
{
   if($type_event === 'sale_complete')
   {
       $files = json_decode(file_get_contents('article.json'), true);
       $key = array_search($custom_field->item_id, array_column($files['articles'], 'id'));
       if($key !== false )
       {
           $files['articles'][$key]['stock'] = $files['articles'][$key]['stock'] - 1;
           file_put_contents('article.json', json_encode($files, JSON_PRETTY_PRINT|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE));
           echo 'success';
       }
       else{
           echo 'key dont exist';
       }

   }
   else{
       echo 'other event : '.$type_event;
   }
}
else{
    echo 'not allowed';
}
