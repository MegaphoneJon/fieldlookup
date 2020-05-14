<?php
use CRM_Fieldlookup_ExtensionUtil as E;

class CRM_Fieldlookup_BAO_FieldLookup extends CRM_Fieldlookup_DAO_FieldLookup {

  /**
   * Check to see if a given set of fields has corresponding field lookup groups.
   *
   * @param array $fields The fields to check to see if they trigger a reverse lookup.
   * @param string $entity The name of the entity being checked.
   * @return array
   *
   */
  public static function getReverseLookupGroups($fields, $entity = NULL) {
    // FIXME: We should cache the API lookup here.
    // Return nothing if no fields are passed.
    if (!$fields) {
      return [];
    }
    if (in_array($entity, ['Individual', 'Organization', 'Household'])) {
      $entity = 'Contact';
    }
    $fieldLookupGroups = civicrm_api3('FieldLookupGroup', 'get', [
      'field_1_entity' => $entity,
      'field_1_name' => ['IN' => $fields],
      'lookup_type' => "reverse",
      'options' => ['limit' => 0],
    ]);
    return $fieldLookupGroups['values'];
  }

}
