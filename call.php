<?php
function getTempFileName () {
  return sys_get_temp_dir()."/time_offset.txt";
}

function getCurrentOffset() {
  $file = getTempFileName();
  if(file_exists($file)) {
    return file_get_contents($file);
  }
  return 0;
}

function HourPlus() {
  $curOffset = getCurrentOffset();
  return file_put_contents(getTempFileName(), (($curOffset+1)%24));
}

function HourMinus() {
  $curOffset = getCurrentOffset();
  return file_put_contents(getTempFileName(), (($curOffset-1)%24));
}


$methods = ["HourPlus", 
            "HourMinus", 
            "CurrentOffset"
            ];

$action = isset($_GET["action"]) ? $_GET["action"] : "CurrentOffset";


if(array_keys($methods, $action, true)) {
  switch($action) {
    case "HourPlus":
      $funcResp = HourPlus();
      $respBody = json_encode(["status"=>"ok", 
                        "offset"=>getCurrentOffset(),
                        "HourPlus"=>$funcResp]);
    break;
    case "HourMinus":
      $funcResp = HourMinus();
      $respBody = json_encode(["status"=>"ok", 
                        "offset"=>getCurrentOffset(),
                        "HourMinus"=> $funcResp]);
    break;
    case "CurrentOffset":

      $respBody = json_encode(["status"=>"ok", 
                        "offset"=>getCurrentOffset()]);
    break;
  }
  header('Content-Type: application/json;charset=utf-8');
  echo $respBody;
}

?>
