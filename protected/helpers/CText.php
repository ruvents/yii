<?php

class CText
{
  /**
   * https://github.com/excelwebzone/EWZTextBundle/blob/master/Templating/Helper/TextHelper.php
   * Truncates +text+ to the length of +length+ and replaces the last three characters with the +truncate_string+
   * if the +text+ is longer than +length+.
   */
  public static function truncate($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
  {
    if ($text == '')
      return null;

    if (strlen($text) > $length)
    {
      $truncate_text = substr($text, 0, $length - strlen($truncate_string));

      if ($truncate_lastspace)
        $truncate_text = preg_replace('/[\s\,\.]+?(\S+)?$/', '', $truncate_text);

      return trim($truncate_text) . $truncate_string;
    }

    return $text;
  }

  public static function humanFileSize($file, $retstring = '%01.2f&nbsp;%s', $system = 'si', $max = null)
  {
    $size = filesize($file);

    // Pick units
    $systems['si']['prefix'] = array('B', 'K', 'MB', 'GB', 'TB', 'PB');
    $systems['si']['size']   = 1000;
    $systems[':)']['prefix'] = array('b', 'k', 'mb', 'gb', 'tb', 'pb');
    $systems[':)']['size']   = 1000;
    $systems['bi']['prefix'] = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
    $systems['bi']['size']   = 1024;
    $sys = isset($systems[$system]) ? $systems[$system] : $systems['si'];

    // Max unit to display
    $depth = count($sys['prefix']) - 1;
    if ($max && false !== $d = array_search($max, $sys['prefix'])) {
      $depth = $d;
    }

    // Loop
    $i = 0;
    while ($size >= $sys['size'] && $i < $depth) {
      $size /= $sys['size'];
      $i++;
    }

    return sprintf($retstring, $size, $sys['prefix'][$i]);
  }
}