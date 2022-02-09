<?php
//Client params: key, secret, platform
include "../private/PostcodeNl/Client.php";
$client = new App\Services\PostcodeNl\Client("803D1VXYMNZhSoyiRJl4W3TWRcuVIoiBX9haTvuvjLg", "A6YOkIrL3FYPcnI02hl7xLmueWc5WxuJZPsqLPFfFD794MUQRK", 'example proxy');
try{
    echo json_encode($client->dutchAddressByPostcode($_POST['post_code'], intval($_POST['house_number']), $_POST['h_addition']));
}
catch(\App\Services\PostcodeNl\Exception\NotFoundException $e){
    echo 404;
}
catch(\Throwable $e){
    echo 400;
}