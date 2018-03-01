# NetAcuity
A PHP client for NetAcuity, a geoip lookup service.

[![Build Status](http://img.shields.io/travis/traderinteractive/netacuity-php.svg?style=flat)](https://travis-ci.org/traderinteractive/netacuity-php)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/traderinteractive/netacuity-php.svg?style=flat)](https://scrutinizer-ci.com/g/traderinteractive/netacuity-php/)
[![Code Coverage](http://img.shields.io/coveralls/traderinteractive/netacuity-php.svg?style=flat)](https://coveralls.io/r/traderinteractive/netacuity-php)

[![Latest Stable Version](http://img.shields.io/packagist/v/traderinteractive/netacuity.svg?style=flat)](https://packagist.org/packages/traderinteractive/netacuity)
[![Total Downloads](http://img.shields.io/packagist/dt/traderinteractive/netacuity.svg?style=flat)](https://packagist.org/packages/traderinteractive/netacuity)
[![License](http://img.shields.io/packagist/l/traderinteractive/netacuity.svg?style=flat)](https://packagist.org/packages/traderinteractive/netacuity)

## Requirements
This library requires PHP 7.0, or newer.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add
`traderinteractive/netacuity` as a dependency to your `composer.json` file.

## User Testing  
This package includes a test script in the `/bin` directory.  
To test this script from the `/bin` (assuming all composer dependencies hasve been installed) directory run:  
```sh
./netacuity your_user_token the_database_id the_ip_address_to_check
```
