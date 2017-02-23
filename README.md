Editors Choice
================

This extension is meant to provide improvements for TYPO3.
It is used as an incubator of possible future TYPO3 Core Features.

Features
--------

### Show references

- If a content element is referenced by insert records or a translation this will be shown above the content element (in edit view)
- If a page is used by another via 'show content from this page' it will be shown above the page element (in edit view)


### Dereference element in Page module

The content element "Insert records" in page module shows the list of referenced elements. This is extended, the context
menu on single items now contains a "detach" menu entry. On click, the selected element is "dereferenced": The original
referenced content element is copied below the "Insert records" element, and the reference from the "Insert record" element
is removed.

### Insert records element allows only content elements having a flag set

The suggest wizard of "Insert records" content element is configured to only allow selection of records that
have flag "Allow as insert record element" in "Access" tab selected.

Showing this checkbox for a sub-part of the tree only can be done with pageTsConfig by adding
"TCEFORM.tt_content.enable_reference.disabled = 1" globally and "TCEFORM.tt_content.enable_reference.disabled = 0" to
the page tree section with according content element pool.

Note if multiple different records types additonal to tt_content are configured to be allowed as "Insert records" record
(eg. records from ext:news), the DB field and TCA configuration "enable_reference" must be added to those tables too,
 otherwise an SQL error is raised and the suggest wizards shows no results.
 