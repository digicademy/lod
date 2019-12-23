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
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY prefix (prefix),
    KEY iri (iri),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_iri (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    type int(11) DEFAULT '0' NOT NULL,
    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,
    value varchar(255) DEFAULT '' NOT NULL,

    namespace int(11) DEFAULT '0' NOT NULL,
    representations int(11) DEFAULT '0' NOT NULL,

    tablename varchar(255) DEFAULT '' NOT NULL,
    record varchar(255) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY type (type),
    KEY label (label),
    KEY value (value),
    KEY namespace (namespace),
    KEY representations (representations),
    KEY tablename (tablename),
    KEY record (record),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_bnode (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,
    value varchar(255) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY label (label),
    KEY value (value),

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
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

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
    path varchar(255) DEFAULT '' NOT NULL,
    query varchar(255) DEFAULT '' NOT NULL,
    fragment varchar(255) DEFAULT '' NOT NULL,
    content_type varchar(255) DEFAULT '' NOT NULL,
    content_language varchar(10) DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY parent (parent),
    KEY scheme (scheme),
    KEY authority (authority),
    KEY content_type (content_type),
    KEY content_language (content_language)

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_statement (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    subject varchar(255) DEFAULT '' NOT NULL,
    predicate varchar(255) DEFAULT '' NOT NULL,
    object varchar(255) DEFAULT '' NOT NULL,

    name int(11) DEFAULT '0' NOT NULL,
    term int(11) unsigned DEFAULT '0' NOT NULL,
    records int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY subject (subject),
    KEY predicate (predicate),
    KEY object (object),
    KEY term (term),
    KEY records (records),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_term (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    label varchar(255) DEFAULT '' NOT NULL,
    comment text NOT NULL,

    statements int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY label (label),
    KEY statements (statements),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_domain_model_vocabulary (

    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    label varchar(255) DEFAULT '' NOT NULL,
    comment varchar(255) DEFAULT '' NOT NULL,

    terms int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime int(11) unsigned DEFAULT '0' NOT NULL,
    endtime int(11) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY pid (pid),

    KEY label (label),
    KEY terms (terms),

) ENGINE=InnoDB;

CREATE TABLE tx_lod_vocabulary_term_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)

) ENGINE=InnoDB;

CREATE TABLE tx_lod_statement_record_mm (

    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

    ident varchar(255) DEFAULT '' NOT NULL,
    tablenames varchar(255) DEFAULT '' NOT NULL,
    fieldname varchar(255) DEFAULT '' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)

) ENGINE=InnoDB;

CREATE TABLE pages (
    statements int(11) unsigned DEFAULT '0' NOT NULL,

    KEY statements (statements)
);

CREATE TABLE tt_content (
    statements int(11) unsigned DEFAULT '0' NOT NULL,

    KEY statements (statements)
);
