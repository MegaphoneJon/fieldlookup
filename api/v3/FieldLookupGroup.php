<?php
use CRM_Fieldlookupgroup_ExtensionUtil as E;

/**
 * FieldLookupGroup.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_field_lookup_group_create_spec(&$spec) {
  $params['field_1_entity']['api.required'] = 1;
  $params['field_1_name']['api.required'] = 1;
  $params['field_2_entity']['api.required'] = 1;
  $params['field_2_name']['api.required'] = 1;
  $params['lookup_type']['api.required'] = 1;
  $params['lookup_operator']['api.required'] = 1;
}

/**
 * FieldLookupGroup.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_field_lookup_group_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * FieldLookupGroup.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_field_lookup_group_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * FieldLookupGroup.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_field_lookup_group_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
