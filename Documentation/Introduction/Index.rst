.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Introduction
------------

What does it do?
^^^^^^^^^^^^^^^^

The LOD extension provides a semantic layer for TYPO3 making it possible to describe and offer
any record in your database as Linked Open Data. The extension features an out of the box API
supporting broadly accepted serialization formats (RDFa, RDF/XML, Turtle, NT, JSON-LD), an
IRI resolver for creating permalinks, well known RDF vocabularies (like schema.org,
SKOS, FOAF etc.) for creating structured data (also embedded as JSON-LD in your web pages) and a
plugin for creating and publishing your own LOD vocabularies.

Features
^^^^^^^^

- Implementation of the RDF data model for TYPO3 using the backend interface for editing structured data
- (Automatically) create IRIs (Internationalized Resource Identifiers) for any record in the system
- Fully configurable permalink resolver for any record in the system
- Import and use established RDF vocabularies (or create your own) to describe your data semantically
- Triple composer based on IRRE with auto suggest for subjects, predicates and objects
- RESTful API implementing the Hydra standard, true content negotiation and a broad range of RDF serializations
- Serializer for embedding structured data in your web pages (as JSON-LD)
- All RDF serializations based on Fluid templates (no external dependencies)
- Vocabulary plugin for creating and publishing LOD vocabularies

Screenshots
^^^^^^^^^^^

**Screenshot 1: Editing of IRIs with namespace management and easy statement generation**

.. figure:: ../Images/iri.png
   :alt: Editing of IRIs with namespace management and easy statement generation

**Screenshot 2: Triple composer with auto suggest for semantic statements**

.. figure:: ../Images/triple-composer.png
   :alt: Triple composer with auto suggest for semantic statements

**Screenshot 3: Restful LOD API with content negotiation, serialization formats as well as search and statement filters**

.. figure:: ../Images/api.png
   :alt: Restful LOD API with content negotiation, serialization formats as well as search and statement filters

Credits
^^^^^^^

This extension is developed by the `Digital Academy <http://www.adwmainz.de/digitalitaet/digitale-akademie.html>`_
of the `Academy of Sciences and Literature | Mainz <http://www.adwmainz.de>`_.

Development
^^^^^^^^^^^

Development takes place on `Github <https://github.com/digicademy/lod>`_.
You are very welcome to submit pull requests.
