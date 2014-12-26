<?php

namespace Spin\Interfaces\Http;

interface Response
{
    /**
     * @param int        $status
     * @param array|null $headers
     */
    public function headers($status, array $headers = null);

    /**
     * @param string $body
     */
    public function render($body);
}