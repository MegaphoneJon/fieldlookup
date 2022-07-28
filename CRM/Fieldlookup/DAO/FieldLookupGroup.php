<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from fieldlookup/xml/schema/CRM/Fieldlookup/FieldLookupGroup.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:0b853486d40070dba49ec8a1c79a2124)
 */
use CRM_Fieldlookup_ExtensionUtil as E;

/**
 * Database access object for the FieldLookupGroup entity.
 */
class CRM_Fieldlookup_DAO_FieldLookupGroup extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_field_lookup_group';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique FieldLookup ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Name of the Field Lookup Group.
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * The entity field 1 belongs to.
   *
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_1_entity;

  /**
   * Field name of field 1, or id if a custom field.
   *
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_1_name;

  /**
   * The entity field 2 belongs to.
   *
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_2_entity;

  /**
   * Field name of field 2, or id if a custom field.
   *
   * @var string
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_2_name;

  /**
   * Specifies how this field is used - chain select, reverse lookup, etc.
   *
   * @var string
   *   (SQL type: varchar(32))
   *   Note that values will be retrieved from the database as a string.
   */
  public $lookup_type;

  /**
   * We use this to compare the value of field 1 to the field_1_values.
   *
   * @var string
   *   (SQL type: varchar(32))
   *   Note that values will be retrieved from the database as a string.
   */
  public $lookup_operator;

  /**
   * Foreign key that links table 1 to table 2.
   *
   * @var string
   */
  public $table_1_fk;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_field_lookup_group';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Field Lookup Groups') : E::ts('Field Lookup Group');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique FieldLookup ID'),
          'required' => TRUE,
          'where' => 'civicrm_field_lookup_group.id',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('Name of the Field Lookup Group.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => TRUE,
          'where' => 'civicrm_field_lookup_group.name',
          'export' => TRUE,
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.51',
        ],
        'field_1_entity' => [
          'name' => 'field_1_entity',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 1 Entity'),
          'description' => E::ts('The entity field 1 belongs to.'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'import' => TRUE,
          'where' => 'civicrm_field_lookup_group.field_1_entity',
          'export' => TRUE,
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Fieldlookup_SelectValues::getEntities',
          ],
          'add' => '5.13',
        ],
        'field_1_name' => [
          'name' => 'field_1_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 1 Name'),
          'description' => E::ts('Field name of field 1, or id if a custom field.'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_field_lookup_group.field_1_name',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
        ],
        'field_2_entity' => [
          'name' => 'field_2_entity',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 2 Entity'),
          'description' => E::ts('The entity field 2 belongs to.'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'import' => TRUE,
          'where' => 'civicrm_field_lookup_group.field_2_entity',
          'export' => TRUE,
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Fieldlookup_SelectValues::getEntities',
          ],
          'add' => '5.13',
        ],
        'field_2_name' => [
          'name' => 'field_2_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 2 Name'),
          'description' => E::ts('Field name of field 2, or id if a custom field.'),
          'required' => TRUE,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_field_lookup_group.field_2_name',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
        ],
        'lookup_type' => [
          'name' => 'lookup_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Lookup Type'),
          'description' => E::ts('Specifies how this field is used - chain select, reverse lookup, etc.'),
          'required' => TRUE,
          'maxlength' => 32,
          'size' => CRM_Utils_Type::MEDIUM,
          'where' => 'civicrm_field_lookup_group.lookup_type',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Fieldlookup_SelectValues::getLookupTypes',
          ],
          'add' => '5.13',
        ],
        'lookup_operator' => [
          'name' => 'lookup_operator',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Lookup Operator'),
          'description' => E::ts('We use this to compare the value of field 1 to the field_1_values.'),
          'required' => TRUE,
          'maxlength' => 32,
          'size' => CRM_Utils_Type::MEDIUM,
          'where' => 'civicrm_field_lookup_group.lookup_operator',
          'default' => '=',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
          'pseudoconstant' => [
            'callback' => 'CRM_Fieldlookup_SelectValues::getLookupOperators',
          ],
          'add' => '5.13',
        ],
        'table_1_fk' => [
          'name' => 'table_1_fk',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Table 1 foreign key'),
          'description' => E::ts('Foreign key that links table 1 to table 2.'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_field_lookup_group.table_1_fk',
          'default' => 'NULL',
          'table_name' => 'civicrm_field_lookup_group',
          'entity' => 'FieldLookupGroup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'localizable' => 0,
          'add' => '5.35',
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'field_lookup_group', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'field_lookup_group', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_field_1_entity' => [
        'name' => 'index_field_1_entity',
        'field' => [
          0 => 'field_1_entity',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_field_lookup_group::0::field_1_entity',
      ],
      'index_field_1_name' => [
        'name' => 'index_field_1_name',
        'field' => [
          0 => 'field_1_name',
        ],
        'localizable' => FALSE,
        'sig' => 'civicrm_field_lookup_group::0::field_1_name',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
