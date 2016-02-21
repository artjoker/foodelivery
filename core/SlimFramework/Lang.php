<?php
  namespace Slim;


  class Lang
  {
    var $strings = array();
    var $unique  = array();

    function __construct ()
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

    function get ($string)
    {
      // check if translate exists
      if (in_array($string, array_keys($this->strings)))
        $result = $this->strings[$string];
      else {
        // if not exist add it to CSV file and return incoming string
        $result = $string;
        if (!in_array($string, $this->unique)) {
          $this->unique[] = $string;
          file_put_contents(PATH_DESIGN . "lang.csv", $string . ",\n", FILE_APPEND);
        }
      }
      return $result;
    }
  }