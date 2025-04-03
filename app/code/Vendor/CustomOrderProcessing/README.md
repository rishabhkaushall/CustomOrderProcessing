## CustomOrderProcessing

## Overview

This module enhances the order processing workflow in Magento 2 by allowing external systems to update order statuses via a REST API and logging changes to a custom database table.

## Installation
1. Place the module in the `app/code/Vendor/CustomOrderProcessing` directory.
2. Run the following commands:
   bash
   php bin/magento module:enable Vendor_CustomOrderProcessing
   php bin/magento setup:upgrade
   php bin/magento cache:clean
   php bin/magento cache:flush