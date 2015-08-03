# Date Dropdown Field

[![Build Status](https://secure.travis-ci.org/dnadesign/silverstripe-datedropdownselectorfield.png?branch=master)](http://travis-ci.org/dnadesign/silverstripe-datedropdownselectorfield)

## Introduction

An assortment of classes to help selecting dates in SilverStripe via dropdown 
lists rather than inputs.

## Maintainer Contact

	* John Milmine (john.milmine@dna.co.nz)

## Requirements

 * SilverStripe 3.x


## Installion

	composer require "dnadesign/silverstripe-datedropdownselectorfield": "dev-master"

## Features

 * `DateSelectorField` - FormField providing 3 dropdown options
 * `DateRangeSelectorField` - Composite Field containing two DateSelectorField
 instances for selecting a range
 * `DateRangeFilter` - SearchFilter for use in ModelAdmin and other contexts for
 use with the DateRangeSelectorField
 * UserForms integration

## License

BSD-3-Clause. See LICENSE.