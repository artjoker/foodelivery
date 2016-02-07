<?php

  namespace Slim;


  class Image
  {

    function __construct()
    {
      require_once PATH_CORE . "PHPThumb/phpthumb.class.php";
    }

    function resize($img, $options, $folder = "", $cache = true)
    {
      $hash = md5($img . serialize($options));
      if (!file_exists($img) || is_dir($img))
        $img = PATH_DESIGN . "img" . DS . "nophoto.png";
      $ext = "";

      $phpThumb = new \phpthumb();
      $phpThumb->setSourceFilename($img);
      $phpThumb->setParameter("q", IMAGE_QUALITY_DEFAULT);
      foreach ($options as $key => $value) {
        $phpThumb->setParameter($key, $value);
        if ($key == "f") $ext = $value;
      }
      if ($ext == "") {
        $phpThumb->setParameter("f", "jpg");
        $ext = "jpg";
      }

      if ($folder != "") {
        $tree = explode("/", $folder);
        $path = IMAGE_CACHE_PATH;
        if (is_array($tree))
          foreach ($tree as $dir) {
            $mk = $path . DS . $dir;
            if (!file_exists($mk)) {
              mkdir($mk);
              chmod($mk, 0777);
            }
            $path .= DS . $dir;
          }
        $folder .= DS;
      }

      $outputFilename = IMAGE_CACHE_PATH . $folder . $hash . "." . $ext;
      if (!$cache && file_exists($outputFilename)) unlink($outputFilename);
      if (!file_exists($outputFilename))
        if ($phpThumb->GenerateThumbnail())
          $phpThumb->RenderToFile($outputFilename);
      $res = str_replace(ROOT, "", $outputFilename);
      return $res;
    }
  }