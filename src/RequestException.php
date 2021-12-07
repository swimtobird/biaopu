<?php
/**
 *
 * User: swimtobird
 * Date: 2021-11-22
 * Email: <swimtobird@gmail.com>
 */

namespace Swimtobird\BiaoPu;


use Throwable;

class RequestException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}