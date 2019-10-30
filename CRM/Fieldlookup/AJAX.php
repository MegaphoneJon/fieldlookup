<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AJAX
 *
 * @author jon
 */
class CRM_Fieldlookup_AJAX {

  public static function chainSelect() {
    // If we ever lock down access to this function, we need to keep in mind that
    // REQUEST_URI can be spoofed.
    $fieldName = basename(strtok($_SERVER["REQUEST_URI"], '?'));
    $fieldId = str_replace('custom_', '', $fieldName);
    $value = CRM_Utils_Request::retrieve('_value', 'String');
    $optionGroupId = civicrm_api3('CustomField', 'getsingle', [
      'return' => ["option_group_id.id"],
      'id' => $fieldId,
    ])['option_group_id.id'];

    // Get an unfiltered list of option values.
    $optionValues = civicrm_api3('OptionValue', 'get', [
      'option_group_id' => $optionGroupId,
      'options' => ['limit' => 0],
    ])['values'];

    // Convert to the api.optionlist
    foreach ($optionValues as $optionValue) {
      $unfiltered[$optionValue['value']] = [
        'key' => $optionValue['value'],
        'value' => $optionValue['label'],
      ];
    }

    // Get the field lookup values so we can filter.
    $fieldLookups = civicrm_api3('FieldLookup', 'get', [
      'sequential' => 1,
      'field_lookup_group_id.field_2_name' => "custom_56",
      'field_1_value' => $value,
      'options' => ['limit' => 0],
    ])['values'];

    // Create the filtered list in api.optionlist format.
    foreach ($fieldLookups as $fieldLookup) {
      $filtered[] = $unfiltered[$fieldLookup['field_2_value']];
    }

    return CRM_Utils_JSON::output($filtered);
  }

}
