-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--


-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the exisiting tables
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_field_lookup`;
DROP TABLE IF EXISTS `civicrm_field_lookup_group`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_field_lookup_group
-- *
-- * Field Lookup Groups for chain select etc.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_field_lookup_group` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique FieldLookup ID',
     `field_1_entity` varchar(64) NOT NULL   COMMENT 'The entity field 1 belongs to.',
     `field_1_name` varchar(64) NOT NULL   COMMENT 'Field name of field 1, or id if a custom field.',
     `field_2_entity` varchar(64) NOT NULL   COMMENT 'The entity field 2 belongs to.',
     `field_2_name` varchar(64) NOT NULL   COMMENT 'Field name of field 2, or id if a custom field.',
     `lookup_type` varchar(32) NOT NULL   COMMENT 'Specifies how this field is used - chain select, reverse lookup, etc.',
     `lookup_operator` varchar(32) NOT NULL  DEFAULT '=' COMMENT 'We use this to compare the value of field 1 to the field_1_values.' 
,
        PRIMARY KEY (`id`)
 
    ,     INDEX `index_field_1_entity`(
        field_1_entity
  )
  ,     INDEX `index_field_1_name`(
        field_1_name
  )
  
 
) COLLATE utf8mb4_general_ci  ;

-- /*******************************************************
-- *
-- * civicrm_field_lookup
-- *
-- * Field Lookups for chain select etc.
-- *
-- *******************************************************/
CREATE TABLE `civicrm_field_lookup` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique FieldLookup ID',
     `field_lookup_group_id` int unsigned NOT NULL   COMMENT 'FK to Field Lookup Group table.',
     `field_1_value` varchar(255) NOT NULL   COMMENT 'This is compared to the value submitted.',
     `field_1_value_2` varchar(255) NULL   COMMENT 'Used when your lookup operator requires two values (e.g. BETWEEN)',
     `field_2_value` varchar(255) NOT NULL   COMMENT 'This is the value(s) returned from a lookup on field 1.' 
,
        PRIMARY KEY (`id`)
,       INDEX `field_1_value`(field_1_value), INDEX `field_1_value_2`(field_1_value_2)
 
,          CONSTRAINT FK_civicrm_field_lookup_field_lookup_group_id FOREIGN KEY (`field_lookup_group_id`) REFERENCES `civicrm_field_lookup_group`(`id`) ON DELETE CASCADE  
) COLLATE utf8mb4_general_ci    ;

 
