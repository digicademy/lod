﻿.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


Reference
---------

This section gives an overview on all TypoScript properties of the LOD extension.

::

### TSCONFIG SETTINGS ###

tx_lod {
    settings {
        identifierGenerator {

            tx_lod_domain_model_iri {

                # type = Digicademy\Lod\Generator\UidIdentifierGenerator,
                # type = Digicademy\Lod\Generator\UuidIdentifierGenerator,
                # type = Digicademy\Lod\Generator\ForeignRecordTablenameUidIdentifierGenerator,
                # type = Digicademy\Lod\Generator\ForeignRecordFieldIdentifierGenerator,
                # type = Vendor\Package\My\Generator

                Digicademy\Lod\Generator\UidIdentifierGenerator {
                    entityPrefix = E
                    propertyPrefix = P
                }
                Digicademy\Lod\Generator\UuidIdentifierGenerator {
                    entityPrefix = E
                    propertyPrefix = P
                    xmlConformance = 1
                }
                Digicademy\Lod\Generator\ForeignRecordTablenameUidIdentifierGenerator {
                    entityPrefix = E
                    propertyPrefix = P
                    includeTablename = 1
                }
                Digicademy\Lod\Generator\ForeignRecordFieldIdentifierGenerator {
                    entityPrefix = E
                    propertyPrefix = P
                    foreignFieldName = my_foreign_field
                }
            }

            tx_lod_domain_model_bnode {

                # type = Digicademy\Lod\Generator\UidIdentifierGenerator,
                # type = Digicademy\Lod\Generator\UuidIdentifierGenerator,
                # type = Vendor\Package\My\Generator

                Digicademy\Lod\Generator\UidIdentifierGenerator {
                    bnodePrefix = b
                }
                Digicademy\Lod\Generator\UuidIdentifierGenerator, {
                    bnodePrefix = b
                    xmlConformance = 1
                }
            }
        }

        # tables also have to be registered in extension configuration
        tableTracking {
            tx_my_table {

                track = 1
                iriPidList = 1,2,3,...
                iriPidList.recursive = 1
                hideUnhide = 1
                deleteUndelete = 1

                # stdWrap on all properties and tracked record as $cobj->data
                iri {
                    type =
                    namespace =
                    label =
                    label_language =
                    comment =
                    comment_language =
                }

                # stdWrap on all properties and tracked record as $cobj->data
                representations {
                    1 {
                        pid =
                        scheme =
                        authority  =
                        path =
                        query =
                        fragment =
                        content_type =
                        content_language =
                    }
                    2 { ... }
                }

                # stdWrap on all properties and tracked record as $cobj->data
                statements {
                    1 {
                        pid =
                        predicate =
                        object =
                        object_type =
                        graph = 1
                        recursion = 1
                    }
                    2 { ... }
                }

            }
        }

        iriTypeFilter {
            subject = 1,2
            predicate = 1
            object = 1,2
        }

        iriLabel {
            displayPattern = ###NAMESPACE_PREFIX###, ###NAMESPACE_IRI###, ###IRI_VALUE, ###IRI_LABEL###
        }

    }
}

### RESOLVER / LINK HANDLER CONFIGURATION ###

# in PageTSconfig

TCEMAIN.linkHandler.MY_HANDLER_KEYWORD {
    handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
    label = LLL:EXT:my_ext/Resources/Private/Language/locallang.xlf:link.customTab
    configuration {
        table = my_table
    }
    scanBefore = page
}

# in TypoScript

config.recordLinks.MY_HANDLER_KEYWORD {
    typolink {
        parameter = PAGE_ID
        additionalParams.data = field:uid
        additionalParams.wrap = &tx_my_plugin[record]=|&tx_my_plugin[action]=myAction&tx_my_plugin[controller]=MyController
        section.data = field:fragment
        useCacheHash = 1
    }
}

### IRI RECORD LINK CONFIGURATION ###

- two thoughts:
-- always link records in the system via its IRI; resolving to the real record instance via representation
-- use the IRI information to create rich information panels around links (cf. refer)

# TSConfig

TCEMAIN.linkHandler.iri {
    handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
    label = IRI
    configuration {
        table = tx_lod_domain_model_iri
        storagePid =
        hidePageTree = 1
    }
    scanBefore = page
}

# TypoScript

config.recordLinks.iri {
    typolink {
        parameter =
        additionalParams.data = field:value
        additionalParams.wrap = &tx_lod_api[iri]=|&type=1991
        useCacheHash = 0
    }
}

### LOD API CONFIGURATION ###

plugin.tx_lod_api {

    settings {

        general {
#            hydraEntryPoint =
        }

        list {
            additionalPidList = page1, page2, page3
        }

#        show {
#        }

        # map items to generic domainObject
        persistence.classes {
            My\Class.mapping.tableName = tx_extension_domain_model_xyz
        }

        # URI resolver configuration
        resolver {
            # each scheme can have its own custom resolver
#            scheme {
#                # each resolver should implement one standard resolving mechanism for all authorities in this scheme
#                # it should also respect special configurations specified authorities that should be treated different
#                authority {
#                    # typolink configuration ($cObj->data is current representation record)
#                }
#            }

            t3 {
                # the t3 scheme implements the TYPO3 linkhandler (from 8.7 onwards but with backwards comp. for 7.6)
                # each authority equals / can have it's own linkhandler (=linkhander keyword)
                # the record authority implements the generic record linkhandler
                record < config.recordLinks.image
            }

            http {
                my\.domain\.com {
                    # typolink configuration ($cObj->data is current representation record)
                }
            }

            https < .http
        }
    }
}

### SERIALIZER CONFIGURATION ###

plugin.tx_lod_serializer {
    settings {
        selectedIri = UID
        mode = embedded
        apiPage = PID
        apiPath = /PATH/TO/API
        recordToArgumentMapping {
            pages {
                argumentName = id
            }
            tx_hisodat_domain_model_persons {
                pluginNamespace = tx_hisodat_registers
                argumentName = person
            }
        }
        format {
            default = jsonld
            tx_my_table = FORMAT (jsonld, ttl, rdfxml, nt)
        }
    }
}

page.headerData.333 = USER
page.headerData.333 {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    extensionName = Lod
    pluginName = Serializer
    vendorName = Digicademy
    persistence.storagePid = PID
    settings < plugin.tx_lod_serializer.settings
    view < plugin.tx_lod_serializer.view
}

### SPEAKING URIs CONFIGURATION ###

#routeEnhancers:
#  ApiPlugin:
#    type: Extbase
#    limitToPages:
#      - x
#      - y
#    extension: Lod
#    plugin: Api
#    routes:
#      -
#        routePath: '/id/{iri}'
#        _controller: 'Api::about'
#        _arguments:
#          iri: iri
#    defaultController: 'Api::about'
#  PageTypeSuffix:
#    type: PageType
#    default: ''
#    map:
#      .html: 0
#      /about.html: 1991
#      /about.rdf: 2004
#      /about.ttl: 2011
#      /about.nt: 2013
#      /about.json: 2014
