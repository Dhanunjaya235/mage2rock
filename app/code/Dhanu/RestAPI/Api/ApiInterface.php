<?php

namespace Dhanu\RestAPI\Api;

interface ApiInterface
{
    /**
     * GET for Post api
     * @param string $value
     * @return string
     */

    public function getData($value);
}
