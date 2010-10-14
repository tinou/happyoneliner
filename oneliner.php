<?php

if (!function_exists(fputcsv)) {
function fputcsv($fh, $arr)
{
  $csv = "";
  while (list($key, $val) = each($arr))
  {
    $val = str_replace('"', '""', $val);
    $csv .= '"'.$val.'",';
  }
  $csv = substr($csv, 0, -1);
  $csv .= "\n";
  if (!@fwrite($fh, $csv))
    return FALSE;
}
}

function read_data () {
  $res = array();
  if (($handle = fopen('oneliner.csv', 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
      $res []= $data;
    }
    fclose($handle);
  }
  return $res;
}

function write_data($data) {
  $fp = fopen('oneliner.csv', 'a+');
  fputcsv($fp, $data);
  fclose($fp);
}

function handle_post_data() {
  if (array_key_exists('submit', $_POST)) {
    $pseudo  = $_POST['pseudo'];
    $message = $_POST['message'];
    $time    = time();
    write_data(array($time, $pseudo, $message));
    header('Location: ' . ($_SERVER['REQUEST_URI']));
  }
}

$css = <<<CSS
input {
       font-family: Verdana, Arial, Helvetica, sans-serif;
       font-size: 11px;
       color: #000000;
       border-width: 1px;
       border-style: solid;
       border-color: #000000;
       background-color: #fffebe;
      }

BODY { 
  background-color: #fffebe;
  scrollbar-face-color: #fffebe;
  scrollbar-shadow-color: #CCCCCC;
  scrollbar-3dlight-color: #fffebe;
  scrollbar-track-color: #fffebe;
  scrollbar-arrow-color:#CCCCCC;
  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; text-decoration: none 
      }

.pseudo {
  color: #FF6600
}

.pseudobracket {
  color: #7DBFF1
}

CSS;

handle_post_data();

$d = read_data();
$rev_d = array_reverse ($d);

echo '<html><head><title>Oneliner</title>';

echo '<style type="text/css">';
echo $css;
echo '</style></head><body>';

echo '<img src="oneliner.jpg" height="40" />';

echo '<form method="post">';
echo '<input type="text" size="8" maxlength="15" name="pseudo" value="pseudo"></input>';
echo '<input type="text" size="25" name="message" value="message-pourri" onclick="this.value=\'\'"></input>';
echo '<input type="submit" name="submit" value="+"></input>';
echo '</form>';

echo '<ul>';
foreach ($rev_d as $line) {
  echo '<li>';
  echo '<span class="pseudobracket">[</span>'
    .'<span class="pseudo">' . $line[1] . '</span>'
    .'<span class="pseudobracket">]</span> '
    .'<span class="message">' . $line[2] . '</span>';
  echo '</li>';
}
echo '</ul>';
echo '</body></html>';

?>
