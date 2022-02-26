<?php
/**
 * Copyright © Manish R. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MR\CustomerImport\Model\Customer;

use MR\CustomerImport\Api\ImportInterface;
use Magento\Framework\ObjectManagerInterface;

class ProfileFactory
{
    /**
     * Object manager
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     * @throws \Exception
     */
    public function create(string $type): ImportInterface
    {

        if ($type === "csv") {
            $class = CsvImporter::class;
        } elseif ($type === "json") {
            $class = JsonImporter::class;
        } else {
            throw new \Exception("Unsupported Profile type specified");
        }
        return $this->objectManager->create($class);
    }
}
