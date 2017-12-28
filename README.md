# NetAcuity
A PHP client for NetAcuity, a geoip lookup service.

[![Build Status](http://img.shields.io/travis/dominionenterprises/netacuity-php.svg?style=flat)](https://travis-ci.org/dominionenterprises/netacuity-php)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/dominionenterprises/netacuity-php.svg?style=flat)](https://scrutinizer-ci.com/g/dominionenterprises/netacuity-php/)
[![Code Coverage](http://img.shields.io/coveralls/dominionenterprises/netacuity-php.svg?style=flat)](https://coveralls.io/r/dominionenterprises/netacuity-php)

[![Latest Stable Version](http://img.shields.io/packagist/v/dominionenterprises/netacuity.svg?style=flat)](https://packagist.org/packages/dominionenterprises/netacuity)
[![Total Downloads](http://img.shields.io/packagist/dt/dominionenterprises/netacuity.svg?style=flat)](https://packagist.org/packages/dominionenterprises/netacuity)
[![License](http://img.shields.io/packagist/l/dominionenterprises/netacuity.svg?style=flat)](https://packagist.org/packages/dominionenterprises/netacuity)

## Requirements
This library requires PHP 5.4, or newer.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add
`dominionenterprises/netacuity` as a dependency to your `composer.json` file.

## User Testing  
This package includes a test script in the `/bin` directory.  
To test this script from the `/bin` (assuming all composer dependencies hasve been installed) directory run:  
```sh
./netacuity your_user_token the_database_id the_ip_address_to_check
```
