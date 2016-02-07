<?php
  namespace Slim;


  class Lang
  {
    var $strings = array();

    function __construct()
    {
      $csv     = fopen(PATH_DESIGN . "lang.csv", "r");
      $titles  = array_values(fgetcsv($csv, 4096, ","));
      $current = array_search(LANG, $titles);
      while (($data = fgetcsv($csv, 4096, ",")) !== FALSE)
        foreach ($data as $key => $value)
          if ($key == $current)
            $this->strings[$data[0]] = $value;
      fclose($csv);
    }

    function get($string)
    {
      if (in_array($string, $this->strings))
        $result = $this->strings[$string];
      else
        $result = $string;
      return $result;
    }
  }