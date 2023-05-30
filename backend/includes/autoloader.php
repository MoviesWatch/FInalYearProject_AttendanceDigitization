<?php

spl_autoload_register(function ($toLoad) {
  $classPath = $_SERVER["DOCUMENT_ROOT"] . "/classes/" . $toLoad . ".class.php";
  $traitsPath = $_SERVER["DOCUMENT_ROOT"] . "/utils/" . $toLoad . ".class.php";
  if (file_exists($classPath)) {
    include $classPath;
  } elseif (file_exists($traitsPath)) {
    include $traitsPath;
  }
});
