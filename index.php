<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/inc.config.php';

$json = array(
  "method"=>"login",
  "params" => array(
      "appType" => "Kasa_Android",
      "cloudUserName" => TPLINK_USERNAME,
      "cloudPassword" => TPLINK_PASSWORD,
      "terminalUUID" => ""
  )
);

$request = new \cURL\Request('https://wap.tplinkcloud.com');
$request->getOptions()
    ->set(CURLOPT_TIMEOUT, 5)
    ->set(CURLOPT_POSTFIELDS, json_encode($json))
    ->set(CURLOPT_HTTPHEADER, array('Content-Type: application/json'))
    ->set(CURLOPT_RETURNTRANSFER, true);
$response = $request->send();
$feed = json_decode($response->getContent());

$token = $feed->result->token;

$json = array(
  "method"=>"getDeviceList"
);

$request = new \cURL\Request('https://wap.tplinkcloud.com?token=' . $token);
$request->getOptions()
    ->set(CURLOPT_TIMEOUT, 5)
    ->set(CURLOPT_POSTFIELDS, json_encode($json))
    ->set(CURLOPT_HTTPHEADER, array('Content-Type: application/json'))
    ->set(CURLOPT_RETURNTRANSFER, true);
$response = $request->send();
$feed = json_decode($response->getContent());

$devices = $feed->result->deviceList;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>TP Link Token</title>
  </head>
  <body>
    <?php

    echo "Token: " . $token . "<br><br>";

    $i = 1;
    foreach($devices as $device){
      // var_dump($device);

      $json = array(
         "method" => "passthrough",
         "params" => array(
           "deviceId" => $device->deviceId,
           "requestData" => json_encode(array(
             "system" => array(
               "get_sysinfo" => null,
             ),
             "emeter" => array(
               "get_realtime" => null,
             ),
           )),
         ),
       );

      $request = new \cURL\Request('https://wap.tplinkcloud.com?token=' . $token);
      $request->getOptions()
          ->set(CURLOPT_TIMEOUT, 5)
          ->set(CURLOPT_POSTFIELDS, json_encode($json))
          ->set(CURLOPT_HTTPHEADER, array('Content-Type: application/json'))
          ->set(CURLOPT_RETURNTRANSFER, true);
      $response = $request->send();
      $feed = json_decode($response->getContent());
      $responseData = json_decode($feed->result->responseData);
      $deviceState = $responseData->system->get_sysinfo->relay_state;

      echo "Device " . $i . ". " . $device->alias . "<br>";
      echo "Type: " . $device->deviceName . " (" . $device->deviceModel . ")<br>";
      echo "ID: " . $device->deviceId . "<br>";
      echo "Url: " . $device->appServerUrl . "<br>";
      echo "State: ";
      echo ($deviceState == 0) ? "Off" : "On";
      echo "<br><br>";
      $i++;
    }

    ?>
  </body>
</html>
