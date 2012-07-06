<?php

/*
    FAAS (figlet as a service)
    Copyright (C) 2012  Andreas Jansson

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

header('Content-type: text/plain');

function fontnames() {
  $names = array();
  $filenames = glob('/usr/share/figlet/*.flf');
  foreach($filenames as $filename) {
    $names[] = str_replace('.flf', '', basename($filename));
  }
  return $names;
}

if(!isset($_GET['s'])) {
  $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

?>
f1g.lt

WELCOME

Please add ?s=whatever to the URL
If you want, you can change font by adding &f=fontname at the end

Examples:

  <?= $url ?>?s=hello
  <?= $url ?>?s=different+font&f=block

Here are the available fonts:
  
<?php

  echo shell_exec('showfigfonts');
  exit();
}

$string = $_GET['s'];

$f = '';
if(isset($_GET['f'])) {
  $font = $_GET['f'];
  if(in_array($font, fontnames()))
    $f = "-f$font";
}

$name = tempnam('/tmp', 'figlet');
file_put_contents($name, $string);

echo shell_exec("figlet -w120 $f < $name");
exit();
