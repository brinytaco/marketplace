# Dem_HelpDesk module

The Dem_HelpDesk module provides a support-ticket system, providing a database-driven
correspondence mechanism to record and track issues and service requests ("Cases")
throughout their life cycles.

## Caseitem vs. Case

Because "case" is a php keyword, it's usage as a class identifier is forbidden.
As a result, all class names and references will be regarded as "Caseitem" as
needed.

A custom router is added to provide for URI matching of "case" and dispatching
to caseitem controller as needed.

## Installation details

Before disabling or uninstalling this module, note that the following modules
depend on this module:

- n/a

