<?php

class CRM_Fieldlookup_SelectValues {

  private static $entities = [];
  /**
   * get a list of the available entities.
   *
   * @return array List of entities in Civi.
   */
  public static function getEntities() {
    if (self::$entities) {
      return self::$entities;
    }
    $result = civicrm_api3('Entity', 'get')['values'];
    $return = [];
    foreach ($result as $entity) {
      $return[$entity] = $entity;
    }
    self::$entities = $return;
    return self::$entities;
  }

  public static function getFieldTypes() {
    return [
      'core' => ts('Core Field'),
      'custom' => ts('Custom Field'),
    ];
  }

  public static function getLookupTypes() {
    return [
      'chain-select' => ts('Chain Select'),
      'reverse' => ts('Reverse Select ("Roll-up")'),
    ];
  }

  public static function getLookupOperators() {
    $operators = CRM_Core_DAO::acceptedSQLOperators();
    return (array_combine($operators, $operators));
  }

}
