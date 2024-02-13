<?php

namespace Dhanu\RestAPI\Model;

use Psr\Log\LoggerInterface;

class API
{
    protected $logger;
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function getData($value)
    {
        $response = ['success' => false];
        try {
            // Implement Your Code here
            $response = ['success' => true, 'message' => $value];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        $returnArray = json_encode($response);
        return $returnArray;
    }
}