<?php

namespace Dhanu\RestAPI\Model;

use Dhanu\RestAPI\Api\CustomerInterface;
use Magento\Customer\Model\CustomerFactory;

class Customer implements CustomerInterface
{
    protected $customerFactory;

    public function __construct(
        CustomerFactory $customerFactory
    ) {
        $this->customerFactory = $customerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function updateEmail($customerId, $newEmail)
    {
        try {
            $customer = $this->customerFactory->create()->load($customerId);
            if ($customer->getId()) {
                $customer->setEmail($newEmail);
                $customer->save();
                return json_encode(['success' => true, 'message' => 'Customer Email Updated Successfully']);
            }
            return json_encode(['success' => false, 'message' => 'Customer with ID ' . $customerId . 'is not found. Invalid ID']);
        } catch (\Exception $e) {
            // Handle exception
            return json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
