# LegacyProductAttributes

Brings back some of the Thelia 1 style of product attributes management.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory
and be sure that the name of the module is LegacyProductAttributes.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/legacy-product-attributes-module:~1.0
```

## Usage

Go to the new *Attributes configuration* tab on the product edition page to configure the price differences
associated to attribute values.

The products using this module should not have any attribute combinations configured, and only use the default pricing.

## Compatibility notes

This module uses some alternative ways to manage the cart and order process.
Most notably, products only have one product sale elements even though this module manage attribute combinations.
Due to this, modules that work on products, the cart or orders may not work properly when used with this module.

This module makes heavy use of javascript to alter the store pages, and may not work properly with templates other
than the default Thelia template. Some manual integration may be required in that case.
