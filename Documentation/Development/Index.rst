﻿.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

Development
===========

* templating
* write your own resolver

Known Issues
------------

* Github Link

Roadmap
-------

### v1.1 ###

- inverse properties
-- if IRI type 2 (property) add group field "inverse of" (bidirectional mm relation shown from both sides)
-- https://www.semanticarts.com/named-property-inverses-yay-or-nay/
-- http://richard.cyganiak.de/blog/2006/06/an-rdf-design-pattern-inverse-property-labels/

- statement/sparql plugin (similar to api plugin)
-- support for basic SPARQL select queries
--- http://base.url/typo3-plugin-page?query=XYZ
-- filter statement list with graph param
--- https://www.w3.org/TR/sparql11-http-rdf-update/
--- http://base.url/typo3-plugin-page?qraph=http://base.url/id/graph => list of statements belonging to graph record

- named graphs
-- add named graph structures to ttl, json-ld and nt templates
-- maybe graph could serve as collection of statements added to records etc.

- backend
- TCA optimization: addRecord fieldControl as popup ?
- Add TSConfig option: statementIriTypeFilter.subject = 1|2
-- see tx_lod_domain_model_statement.php 95,114 and IriUtility 52
-- use case 1: in average statements it is desirable that only entities are allowed in subject position
-- use case 2: in case of a vocabulary properties to be described must be allowed in subject position

- hydra:view has no alternative serialisations in none of the serialisation formats

### v1.2 ###

- vocabulary importer

- api plugin
-- think about cacheable actions => configurable cHash calculation during redirection => check performance

- resolver
-- call different typolink handlers according to $representation->getAuthority();
-- implement language negotiation
