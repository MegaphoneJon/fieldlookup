<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from fieldlookup/xml/schema/CRM/Fieldlookup/FieldLookup.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:52bc931e2e4830f31d1b1642df8d0c54)
 */
use CRM_Fieldlookup_ExtensionUtil as E;

/**
 * Database access object for the FieldLookup entity.
 */
class CRM_Fieldlookup_DAO_FieldLookup extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_field_lookup';

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
   * FK to Field Lookup Group table.
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_lookup_group_id;

  /**
   * This is compared to the value submitted.
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_1_value;

  /**
   * Used when your lookup operator requires two values (e.g. BETWEEN)
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_1_value_2;

  /**
   * This is the value(s) returned from a lookup on field 1.
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $field_2_value;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_field_lookup';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Field Lookups') : E::ts('Field Lookup');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'field_lookup_group_id', 'civicrm_field_lookup_group', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
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
          'where' => 'civicrm_field_lookup.id',
          'table_name' => 'civicrm_field_lookup',
          'entity' => 'FieldLookup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'field_lookup_group_id' => [
          'name' => 'field_lookup_group_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Field Lookup Group ID'),
          'description' => E::ts('FK to Field Lookup Group table.'),
          'required' => TRUE,
          'where' => 'civicrm_field_lookup.field_lookup_group_id',
          'table_name' => 'civicrm_field_lookup',
          'entity' => 'FieldLookup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookup',
          'localizable' => 0,
          'FKClassName' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
        ],
        'field_1_value' => [
          'name' => 'field_1_value',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 1 Value'),
          'description' => E::ts('This is compared to the value submitted.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_field_lookup.field_1_value',
          'table_name' => 'civicrm_field_lookup',
          'entity' => 'FieldLookup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
        ],
        'field_1_value_2' => [
          'name' => 'field_1_value_2',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 1 Value 2'),
          'description' => E::ts('Used when your lookup operator requires two values (e.g. BETWEEN)'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_field_lookup.field_1_value_2',
          'table_name' => 'civicrm_field_lookup',
          'entity' => 'FieldLookup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
        ],
        'field_2_value' => [
          'name' => 'field_2_value',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Field 2 Value'),
          'description' => E::ts('This is the value(s) returned from a lookup on field 1.'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_field_lookup.field_2_value',
          'table_name' => 'civicrm_field_lookup',
          'entity' => 'FieldLookup',
          'bao' => 'CRM_Fieldlookup_DAO_FieldLookup',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => '5.13',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'field_lookup', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'field_lookup', $prefix, []);
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
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
