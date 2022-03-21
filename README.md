# nordigen-api

PHP API for [Nordigen OpenBanking API](https://nordigen.com/en/account_information_documenation/api-documention/overview/). Work in progress!

[![Latest Stable Version](http://poser.pugx.org/pemedina/nordigen-api/v?style=for-the-badge)](https://packagist.org/packages/pemedina/nordigen-api?style=for-the-badge) [![Total Downloads](http://poser.pugx.org/pemedina/nordigen-api/downloads?style=for-the-badge)](https://packagist.org/packages/pemedina/nordigen-api) [![Latest Unstable Version](http://poser.pugx.org/pemedina/nordigen-api/v/unstable?style=for-the-badge)](https://packagist.org/packages/pemedina/nordigen-api) [![License](http://poser.pugx.org/pemedina/nordigen-api/license?style=for-the-badge)](https://packagist.org/packages/pemedina/nordigen-api) [![PHP Version Require](http://poser.pugx.org/pemedina/nordigen-api/require/php?style=for-the-badge)](https://packagist.org/packages/pemedina/nordigen-api)


## Installation
Add the package as a dependency in your `composer.json` file:

``` javascript
require {
    "pemedina/nordigen-api": "dev-master"
}
```
Include the composer autoloader in your script. Set your nordigen API token and create an instance of the API.

``` php
require 'vendor/autoload.php';

use Pemedina/Nordigen;

$nordigen = new Nordigen($secret_id, $secret_key);
```

## Usage

### Accounts
```php
// Access account details.
$account= $nordigen->getDetails($account_id);

// Access account balances.
$balances = $nordigen->getBalances($account_id);

// Access account transactions.
$transactions = $nordigen->getTransactions($account_id);

```
### Agreements
```php
// Retrieve all enduser agreements
$agreements = $nordigen->getAgreements();

// Create enduser agreement
$agreement = $nordigen->createAgreement( $attributes);

// 	Retrieve enduser agreement by id
$agreement = $nordigen->getAgreements($agreement_id);

// Delete End User Agreement.
$agreement = $nordigen->deleteAgreement($agreement_id);

// Accept an end-user agreement via the API.
$agreement = $nordigen->acceptAgreement($agreement_id);

```
### Institutions
```php
// List all available institutions
$nordigen->getInstitutions();

// Get details about a specific Institution
$nordigen->getInstitutions($institution_id);
```
### Requisitions 
```php
// Retrieve all requisitions belonging to the company
$nordigen->getRequisitions();

// Create new requisition
$nordigen->createRequisition([]);

// Retrieve all requisition by id
$nordigen->getRequisition($requisition_id);

// Delete Requisition and all End User Agreements.
$nordigen->deleteRequisition($requisition_id);

```

## Testing
Put `SECRET_ID` and `SECRET_KEY` in your environment. Or put the following in `phpunit.xml`.
``` xml
<php>
    <env name="SECRET_ID" value="secret_id"/>
      <env name="SECRET_KEY" value="secret_key"/>
</php>
```
Run tests with `phpunit (-v) tests`.
