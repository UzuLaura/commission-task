# Laura's Commission task solution

## About script
This script takes data about operations from file and returns calculated commission fees.
* Commission fees are calculated and returned in same currency as operation.
* Currency is rounded to upper bound of the smallest currency item (smallest currency item can be set in `/config/data/currencies.php`).
    * If smallest currency item was not set, currency will be rounded to two decimal points by default.

## Configuration
* Configuration files can be found in `/config` directory.
* To tell script which configuration files you want to use, set paths to files in `.env` file.
* Currency conversion rates can be set in `/config/currencies.php`.
* More explanation about configuration can be found in configuration files.
* More configuration files can be added if needed.

## Running script
1. Open script root directory in your `stdout`.
2. Make sure you have all dependencies listed in `composer.json` installed by running `composer install`.
3. Enter `php script.php file_location` to your cmd. `file_location` is path to your data file. Default data file path is `input.csv`.
    * eg. `php script.php input.csv`