<?php

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
  public function testBasic()
  {
    echo "Simple test is running...\n";
    $this->assertTrue(true);
  }
}
