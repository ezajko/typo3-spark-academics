#
# Table structure for extension 'spark_academics'
#

CREATE TABLE tx_spark_department (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_research_lab (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_research_group (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_project (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_chair (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_study_program (
    landing_page int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_spark_course (
    landing_page int(11) DEFAULT '0' NOT NULL
);
CREATE TABLE tx_spark_person (
    slug varchar(2048) DEFAULT '' NOT NULL
);
