# Stoneholl Order Coupon Grid Filter

Stoneholl Order Coupon Grid Filter adds coupon information and a discount amount to the Magento Open Source order
grid without adding columns to `sales_order_grid` or changing the database schema.

## Features

- A **Coupon Code** column with text filtering. The column is hidden by default and can be enabled from the standard
  **Columns** control.
- A standalone **Coupon Used** filter with **Yes** and **No** options.
- A **Discount Amount (Purchased)** column. The column is hidden by default and can be enabled from **Columns**.
- Compatibility with synchronous and asynchronous sales order grid indexing.
- No preferences, core overrides, database patches, or Adobe Commerce-specific dependencies.

The discount amount comes from `sales_order.discount_amount`, retains Magento's standard sign, and is formatted in
the currency used to place the order. A discount is therefore normally displayed as a negative amount.

## Compatibility

- Declared: Magento Open Source 2.3.7-p4 through 2.4.9
- Declared: PHP 7.4 through 8.5
- MySQL or MariaDB versions supported by the selected Magento release

Use a PHP version supported by the selected Magento release. Adobe Commerce-specific functionality is outside this
module's scope. Current focused runtime checks use Magento Open Source 2.3.7-p4 / PHP 7.4.33 and
Magento Open Source 2.4.9 / PHP 8.5.9. PHP 7.4/8.0/8.5 grammar checks do not establish runtime coverage for
other combinations. These two exact pairs passed focused native runtime, order-grid indexing, CSV export,
configuration and authorization checks; authenticated browser acceptance ran on Magento Open Source 2.4.9.

## Installation with Composer

Stoneholl Core 1.x is required for the offline product registry. Stoneholl Connector is optional.
Install the package from Packagist and enable it with:

```bash
composer require stoneholl/magento2-order-coupon-grid-filter
bin/magento module:enable Stoneholl_Core Stoneholl_OrderCouponGridFilter
bin/magento setup:upgrade
bin/magento cache:flush
```

Run the normal dependency-injection compilation command as required by the deployment mode.

## Upgrade

Back up the application and database, update the package, and run the deployment's normal Magento upgrade, compilation,
and cache-management workflow. Review [CHANGELOG.md](CHANGELOG.md) before upgrading. The module does not own database
tables or data patches.

## Installation from app/code

Place Core at `app/code/Stoneholl/Core` and this module at `app/code/Stoneholl/OrderCouponGridFilter`, then run:

```bash
bin/magento module:enable Stoneholl_Core Stoneholl_OrderCouponGridFilter
bin/magento setup:upgrade
bin/magento cache:flush
```

## Usage

Open **Sales > Orders** in the Admin.

Use the grid's **Columns** control to enable **Coupon Code** or **Discount Amount (Purchased)**. Open **Filters** to
search for a coupon code or select **Coupon Used: Yes** or **Coupon Used: No**.

**Coupon Used: Yes** matches a non-null, non-empty coupon code. **Coupon Used: No** matches a null or empty coupon
code. It reports whether a coupon code was attached to the order, not whether another type of promotion generated a
discount.

## Configuration

Configuration is available under **Stores > Configuration > Stoneholl > Order Coupon Grid Filter**. The settings are
global because the Admin order grid can contain orders from multiple websites and stores.

- **Enable Coupon Code Column and Filter** controls the hidden column and its text filter together.
- **Enable Coupon Used Filter** controls the independent Yes / No filter.
- **Enable Discount Amount Column** controls the hidden purchased-currency column.

All three features are enabled by default and can be switched independently. A disabled component is removed from
the server-side component tree, including rendering, CSV export, and the **Columns** and **Filters** controls. When all features are disabled, the module
does not join `sales_order` to the grid collection.

Magento stores order grid preferences in Admin UI bookmarks. Re-enabling a column may restore its visibility from a
previously saved bookmark; reset the grid view if a different personal default is preferred.

## Architecture and performance

The module registers a specialized collection only for `sales_order_grid_data_source`. When required by an enabled
feature, the collection performs a one-to-one `LEFT JOIN` from `sales_order_grid.entity_id` to
`sales_order.entity_id`. It selects only the fields needed by enabled columns. The Coupon Used filter can use the
joined coupon code without adding it to the result set.

Because the values are read from the canonical order table, they do not depend on when `sales_order_grid` is updated.
This makes the module compatible with both synchronous and asynchronous grid indexing and avoids duplicated data or
index synchronization logic.

Filtering by coupon code may scan rows in `sales_order` because the module deliberately does not add an index or
change Magento's schema. Stores with very large order volumes should assess the query plan against their production
database and workload.

## Uninstall

Disable the module before removing its files, then run Magento's normal setup upgrade and cache-management workflow. The
module does not own database tables. Magento may retain administrator UI bookmarks that mention its columns; reset the
Sales Order grid view if a stale bookmark remains after removal.

## Troubleshooting and support

- Reset the Sales Order grid view if a column does not follow its default visibility.
- Clean Magento configuration cache after changing module state or configuration outside the Admin.
- Confirm that no other extension replaces `sales_order_grid_data_source` incompatibly when fields are missing.
- Community support and update availability are provided through the channel from which the package was obtained.

## Privacy and offline operation

This module reads order coupon and discount values locally for authorized Admin grid requests. It creates no tables,
credentials, telemetry or outbound network requests. It does not transmit order/customer data to Stoneholl or require
remote entitlements. Installed functionality works offline with Core; Connector is optional and is not required for
coupon filtering or exports. Normal Magento Sales permissions control order-grid access; the module's configuration
has its own ACL resource. Exported files follow Magento's standard export handling.

## License

This module is released under the MIT License. See [LICENSE](LICENSE).

## Composer package naming

The canonical Composer name is `stoneholl/magento2-order-coupon-grid-filter`. The former `stoneholl/module-order-coupon-grid-filter` name is a legacy identity. The renamed package replaces that identity at `self.version`, so Composer installs one copy of the Magento module. Existing immutable releases remain unchanged.

When migrating an existing root requirement, back up the project and remove the old requirement without updating, then require the new package with its dependencies:

```bash
composer remove stoneholl/module-order-coupon-grid-filter --no-update
composer require stoneholl/magento2-order-coupon-grid-filter --with-all-dependencies
```

Run the Magento upgrade, compilation and cache commands documented above according to your deployment mode. Keep native `Stoneholl_*` module names and configuration paths unchanged.
