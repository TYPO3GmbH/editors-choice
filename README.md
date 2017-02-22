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