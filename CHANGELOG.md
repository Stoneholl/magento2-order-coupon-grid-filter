# Changelog

## 1.0.0

- Remove disabled coupon/discount components from server-side order-grid traversal so they cannot format missing
  values, apply stale filters/sorting, or leave unprepared option sources and disabled columns in CSV exports.
- Clarify the required offline Core installation, optional Connector, privacy and tested compatibility boundaries.
- Register the canonical product and primary package in the offline Core product registry.

- Added Coupon Code and Discount Amount columns to the Admin order grid.
- Added an independent Coupon Used filter.
- Added Default-scope feature switches and conditional order-table joining.
- Added support for synchronous and asynchronous order-grid indexing.
