# Dem_HelpDesk module

The Dem_HelpDesk module Provides a support-ticket system, providing a database-driven
correspondence mechanism to record and track issues and service requests ("Cases")
throughout their life cycles.

## Topic vs. Case

Because "case" is a php keyword, it's usage as a class identifier is forbidden.
In an effort to remove ambiguity while preventing collision, and since the term
"ticket" is already reserved for other Dem components, "cases" will be herein
regarded as "topics."

This naming convention will pervade all individual case object references. The
term "Case Manager" and "Case Number" will remain in use.

Translation will provide display adjustment of all "Topic" strings in the UI.

## Installation details

Before disabling or uninstalling this module, note that the following modules
depend on this module:

- n/a

