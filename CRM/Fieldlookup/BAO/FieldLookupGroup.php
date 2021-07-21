<?php
use CRM_Fieldlookup_ExtensionUtil as E;

class CRM_Fieldlookup_BAO_FieldLookupGroup extends CRM_Fieldlookup_DAO_FieldLookupGroup {

  /**
   * Create a new FieldLookupGroup based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Fieldlookup_DAO_FieldLookupGroup|NULL
   *
  public static function create($params) {
    $className = 'CRM_Fieldlookup_DAO_FieldLookupGroup';
    $entityName = 'FieldLookupGroup';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
