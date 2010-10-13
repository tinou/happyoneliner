<?php

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

handle_post_data();

$d = read_data();
$rev_d = array_reverse ($d);

echo '<form method=post>';
echo '<input type=text name=pseudo value=Pseudo></input>';
echo '<input type=text name=message></input>';
echo '<input type=submit name=submit value=Go></input>';
echo '</form>';

echo '<ul>';
foreach ($rev_d as $line) {
  echo '<li>';
  echo $line[0].'['.$line[1].']'.$line[2];
  echo '</li>';
}
echo '</ul>';

?>
