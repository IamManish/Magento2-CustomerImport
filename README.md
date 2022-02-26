# Mage2 Module MR CustomerImport

    ``mr/module-customerimport``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Customer Import 

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/MR`
 - Enable the module by running `php bin/magento module:enable MR_CustomerImport`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require mr/module-customerimport`
 - enable the module by running `php bin/magento module:enable MR_CustomerImport`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

- Console Command
 - JSON profile - Place json inside var/import/ folder -php bin/magento customer:import json var/import/sample.json
 - CSV profile - Place CSV inside var/import/ folder -php bin/magento customer:import csv var/import/sample.csv
 - Once we run our customer import script, we also need to make sure to re-index the Customer Grid indexer - php bin/magento indexer:reindex customer_grid 

## Attributes


