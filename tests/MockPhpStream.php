<?php

class MockPhpStream
{
  private static $content = '';
  private $position = 0;

  /** @var resource|null */
  public $context; // Define the property explicitly to avoid deprecation warning

  public function stream_open($path, $mode, $options, &$opened_path)
  {
    $this->position = 0;
    return true;
  }

  public function stream_read($count)
  {
    $data = substr(self::$content, $this->position, $count);
    $this->position += strlen($data);
    return $data;
  }

  public function stream_write($data)
  {
    self::$content = $data;
    return strlen($data);
  }

  public function stream_tell()
  {
    return $this->position;
  }

  public function stream_eof()
  {
    return $this->position >= strlen(self::$content);
  }

  public function stream_stat()
  {
    return [];
  }

  public static function setContent($data)
  {
    self::$content = $data;
  }
}
