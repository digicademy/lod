CREATE TABLE tx_lod_domain_model_namespace (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    prefix varchar(20) DEFAULT '' NOT NULL,
    iri varchar(255) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY prefix (prefix),
    KEY iri (iri),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_iri (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    type int(11) DEFAULT '0' NOT NULL,
    label varchar(255) COLLATE utf8mb4_bin DEFAULT '' NOT NULL,
    label_language varchar(2) DEFAULT '' NOT NULL,
    comment text NOT NULL,
    comment_language varchar(2) DEFAULT '' NOT NULL,
    value varchar(255) COLLATE utf8mb4_bin DEFAULT '' NOT NULL,

    namespace int(11) DEFAULT '0' NOT NULL,
    representations int(11) DEFAULT '0' NOT NULL,

    record varchar(255) DEFAULT '' NOT NULL,
    record_uid int(11) DEFAULT '0' NOT NULL,
    record_tablename varchar(255) DEFAULT '' NOT NULL,

    statements int(11) unsigned DEFAULT '0' NOT NULL,

    prefix_value varchar(255) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    # needed for identifying a copy of a record
    t3_origuid int(11) DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY type (type),
    KEY label (label),
    KEY value (value),
    KEY comment (comment(255)),
    KEY namespace (namespace),
    KEY representations (representations),
    KEY record (record),
    KEY record_uid (record_uid),
    KEY record_tablename (record_tablename),
    KEY statements (statements),
    KEY prefix_value (comment(255))

) ENGINE=InnoDB CHARACTER SET utf8mb4;

CREATE TABLE tx_lod_domain_model_bnode (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,
    value varchar(255) DEFAULT '' NOT NULL,

    statements int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,

    # needed for identifying a copy of a record
    t3_origuid int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY label (label),
    KEY value (value),
    KEY statements (statements),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_literal (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    value text NOT NULL,
    language varchar(2) DEFAULT '' NOT NULL,
    datatype varchar(255) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY value (value(255)),
    KEY language (language),
    KEY datatype (datatype),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_representation (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    # generic 1:1 relation to parent iri
    parent int(11) unsigned DEFAULT '0' NOT NULL,

    scheme varchar(255) DEFAULT '' NOT NULL,
    authority varchar(255) DEFAULT '' NOT NULL,
    path text NOT NULL,
    query text NOT NULL,
    fragment text NOT NULL,
    content_type varchar(255) DEFAULT '' NOT NULL,
    content_language varchar(10) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY parent (parent),
    KEY scheme (scheme),
    KEY authority (authority),
    KEY content_type (content_type),
    KEY content_language (content_language),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_statement (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    subject varchar(255) DEFAULT '' NOT NULL,
    subject_type varchar(255) DEFAULT '' NOT NULL,
    subject_uid int(11) DEFAULT '0' NOT NULL,

    predicate varchar(255) DEFAULT '' NOT NULL,
    predicate_type varchar(255) DEFAULT '' NOT NULL,
    predicate_uid int(11) DEFAULT '0' NOT NULL,

    object varchar(255) DEFAULT '' NOT NULL,
    object_type varchar(255) DEFAULT '' NOT NULL,
    object_uid int(11) DEFAULT '0' NOT NULL,
    object_recursion tinyint(4) unsigned DEFAULT '0' NOT NULL,
    object_inversion tinyint(4) unsigned DEFAULT '0' NOT NULL,

    graph int(11) DEFAULT '0' NOT NULL,

    reference_statements int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    graph_sorting int(11) unsigned DEFAULT '0' NOT NULL,
    iri_sorting int(11) unsigned DEFAULT '0' NOT NULL,
    bnode_sorting int(11) unsigned DEFAULT '0' NOT NULL,

    sys_language_uid int(11) DEFAULT '0' NOT NULL,
    l10n_parent int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY subject (subject),
    KEY subject_uid (subject_uid),
    KEY predicate (predicate),
    KEY predicate_uid (predicate_uid),
    KEY object (object),
    KEY object_uid (object_uid),
    KEY graph (graph),
    KEY reference_statements (reference_statements),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_graph (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    iri int(11) unsigned DEFAULT '0' NOT NULL,
    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,

    statements int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY iri (iri),
    KEY statements (statements),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_vocabulary (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    iri int(11) unsigned DEFAULT '0' NOT NULL,
    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,

    terms int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY iri (iri),
    KEY terms (terms),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_vocabulary_iri_mm (

    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign),

) ENGINE=InnoDB;

CREATE TABLE pages (

    iri int(11) unsigned DEFAULT '0' NOT NULL,

    KEY iri (iri),
);

CREATE TABLE sys_category (

    iri int(11) unsigned DEFAULT '0' NOT NULL,

    KEY iri (iri),
);
