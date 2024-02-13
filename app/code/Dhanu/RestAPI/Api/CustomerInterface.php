<?php

namespace Dhanu\RestAPI\Api;

interface CustomerInterface
{
    /**
     * Update customer email
     *
     * @param int $customerId
     * @param string $newEmail
     * @return string
     */
    public function updateEmail($customerId, $newEmail);
}
