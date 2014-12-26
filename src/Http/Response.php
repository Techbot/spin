<?php

namespace Spin\Http;

use React\Http\Response as BaseResponse;
use Spin\Interfaces;

class Response extends BaseResponse implements Interfaces\Http\Response
{

    /**
     * @param int        $status
     * @param array|null $headers
     */
    public function headers($status, array $headers = null)
    {
        $this->writeHead($status, $headers);
    }

    /**
     * @param string $body
     */
    public function render($body)
    {
        $this->end($body);
    }
}