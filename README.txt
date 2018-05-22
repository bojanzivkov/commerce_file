INTRODUCTION
------------

The Commerce File allows the creation of products that sell access to private files. This could be a doc, image and so on.

This access is controlled by a License entity, which is created for the user
when the product is purchased.

The nature of what a License entity grants is handled by License type plugins.
Each License entity will have one License type plugin associated with it.

A product variation that sells a License will have a configured License type
plugin field value. This acts as template to create the License when a user
purchases that product variation.


REQUIREMENTS
------------

This module requires the following modules:

 * Commerce License (https://drupal.org/project/commerce_license)

INSTALLATION
------------

Install as you would normally install a contributed Drupal module. Visit
https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules for
further information.

CONFIGURATION
-------------

To create products that grant licenses to file:

1 Configure or create a checkout flow which does not allow anonymous checkout.
2 Configure or create an Order Type to use the checkout flow.
3 Configure or create an Order Item Type to use the Order Type, and work with
  Licenses.
4 Configure or create a Product Variation Type to use the Order Item Type, and
  provide Licenses.
5 Configure or create a Product Type that uses the Product Variation Type.
6 Create one or more products that provide licenses. In the product variation,
  configure:
  - The license type
  - The expiration. (Set to unlimited)