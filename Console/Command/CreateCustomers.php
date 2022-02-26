<?php
/**
 * Copyright © Manish R. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MR\CustomerImport\Console\Command;

use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MR\CustomerImport\Api\ImportInterface;
use MR\CustomerImport\Model\Customer\ProfileFactory;
use MR\CustomerImport\Model\Customer;

class CreateCustomers extends Command
{
    protected $importer;

    protected $profileFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Customer
     */
    
    private $customer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var State
     */
    private $state;

    /**
     * CustomerImport constructor.
     * @param ProfileFactory $profileFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProfileFactory $profileFactory,
        Customer $customer,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        State $state
    ) {
        parent::__construct();
        
        $this->profileFactory = $profileFactory;
        $this->customer = $customer;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->state = $state;
    }

    /**
    * {@inheritdoc}
    */
    protected function configure(): void
    {
        $this->setName("customer:import");
        $this->setDescription("Customer Import via CSV & JSON");
        $this->setDefinition([
            new InputArgument(ImportInterface::PROFILE_NAME, InputArgument::REQUIRED, "Profile name ex: sample-csv"),
            new InputArgument(ImportInterface::FILE_PATH, InputArgument::REQUIRED, "File Path ex: sample.csv")
        ]);
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $profileType = $input->getArgument(ImportInterface::PROFILE_NAME);
        $filePath = $input->getArgument(ImportInterface::FILE_PATH);
        $output->writeln(sprintf("Profile type: %s", $profileType));
        $output->writeln(sprintf("File Path: %s", $filePath));

        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);

            if ($importData = $this->getImporterInstance($profileType)->getImportData($input)) {
                $storeId = $this->storeManager->getStore()->getId();
                $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
                
                foreach ($importData as $data) {
                    $this->customer->createCustomer($data, $websiteId, $storeId);
                }

                $output->writeln(sprintf("Total of %s Customers are imported", count($importData)));
                return Cli::RETURN_SUCCESS;
            }

            return Cli::RETURN_FAILURE;
   
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $output->writeln("<error>$msg</error>", OutputInterface::OUTPUT_NORMAL);
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * @param $profileType
     * @return ImportInterface
     */
    protected function getImporterInstance($profileType): ImportInterface
    {
        if (!($this->importer instanceof ImportInterface)) {
            $this->importer = $this->profileFactory->create($profileType);
        }
        return $this->importer;
    }
}