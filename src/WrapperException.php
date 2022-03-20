<?php

namespace Pemedina\Nordigen;

use Exception;

class WrapperException extends Exception
{
  public function __construct($message, $code = 400)
  {
    parent::__construct($message, $code);
  }
}
