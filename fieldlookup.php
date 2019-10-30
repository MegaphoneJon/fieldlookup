<?php

require_once 'fieldlookup.civix.php';
use CRM_Fieldlookup_ExtensionUtil as E;

/**
 * Generate paths on the fly for each chain-select lookup. This is not a great way to do this - ideally
 * we'd have a single route and a URL param - but I'm trying not to rewrite the
 * Civi chain-select code, which hard-codes assumptions that you're using it for state/county purposes.
 * @param array $items
 */
function fieldlookup_civicrm_alterMenu(&$items) {
  $lookupGroups = civicrm_api3('FieldLookupGroup', 'get', [
    'sequential' => 1,
    'return' => ["field_2_name"],
  ])['values'];
  foreach ($lookupGroups as $lookupGroup) {
    $items["civicrm/ajax/chainselect/{$lookupGroup['field_2_name']}"] = [
      'page_callback' => 'CRM_Fieldlookup_AJAX::chainSelectJSON',
    ];
  }
}

function fieldlookup_civicrm_buildForm($formName, &$form) {
  // For now, we're just working on multi-record custom field forms.  Will expand this later.
  if ($formName != 'CRM_Contact_Form_CustomData') {
    return;
  }
  CRM_Core_Resources::singleton()->addScriptFile('fieldlookup', 'js/fieldlookup.js');
  // Collect a list of Select fields, so we can check for field lookups.
  foreach ($form->_elements as $element) {
    if ($element instanceof HTML_QuickForm_select) {
      $selectFields[$element->_attributes['data-api-field']] = $element->_attributes['name'];
    }
  }
  // Are any of these select fields also lookup fields?
  if ($selectFields) {
    $lookupGroupsRaw = civicrm_api3('FieldLookupGroup', 'get', [
      'sequential' => 1,
      'field_1_name' => ['IN' => array_flip($selectFields)],
      'lookup_type' => 'chain-select',
    ]);
  }
  // If any of these ARE lookup fields, configure them as chain selects.
  foreach ($lookupGroupsRaw['values'] as $rawGroup) {
    $lookupGroups[$selectFields[$rawGroup['field_2_name']]] = [
      'chain-parent' => $rawGroup['field_1_name'],
      'chain-child' => $rawGroup['field_2_name'],
    ];
  }

  foreach ($lookupGroups as $key => $lookupGroup) {
    $settings = [
      'control_field' => $lookupGroup['chain-parent'],
      'control-field-name' => $selectFields[$lookupGroup['chain-parent']],
      'data-callback' => "civicrm/ajax/chainselect/{$lookupGroup['chain-child']}",
    ];

    // OK, CRM_Core_Form::addChainSelect() hard-codes assumptions about being for state/county, so
    // let's use our own variant.
    fieldlookup_addChainSelect($key, $settings, $form);

  }
}

/**
 * Create a chain-select target field. Stolen from CRM_Core_Form because it hard-codes
 * references to $form->_chainSelects, which is private and preProcessChainSelectFields()
 * assumes this is a state/country or county/country chain select.
 *
 * @param string $elementName
 * @param array $settings
 *
 * @return HTML_QuickForm_Element
 */
function fieldlookup_addChainSelect($elementName, $settings = [], &$form) {
  $controlElement = $form->_elements[$form->_elementIndex[$settings['control-field-name']]];
  $targetElement = $form->_elements[$form->_elementIndex[$elementName]];

  $props = $settings += [
    'control_field' => NULL,
    'data-callback' => NULL,
    'label' => $targetElement->_label,
    'data-entry-prompt' => "Choose $controlElement->_label first",
    'data-none-prompt' => ts('- N/A -'),
    'placeholder' => empty($settings['required']) ? ts('- none -') : ts('- select -'),
  ];
  CRM_Utils_Array::remove($props, 'label', 'required', 'control_field', 'context', 'control-field-name');

  $props['data-select-prompt'] = $props['placeholder'];
  $props['data-name'] = $elementName;

  // Add the 'crm-chain-select-control' class and data-target to the control field.
  $controlElement->setAttribute('data-target', $elementName);
  $controlElement->_attributes['class'] .= ' crm-chain-select-control';

  // Now update the target field attributes.
  $props['class'] = $targetElement->_attributes['class'] . ' crm-chain-select-target';
  $targetElement->_attributes = array_merge($targetElement->_attributes, $props);
  // If the control field already has a value, pre-filter.
  $controlFieldDefaultValue = $form->_defaultValues[$settings['control-field-name']] ?? FALSE;
  if ($controlFieldDefaultValue) {
    $options = CRM_Fieldlookup_AJAX::chainSelect($targetElement->getAttribute('data-api-field'), $form->_defaultValues[$settings['control-field-name']] ?? FALSE);
    foreach ($options as $k => $option) {
      $filteredOptions[$k] = [
        'attr' => [
          'value' => $option['key'],
        ],
        'text' => $option['value'],
      ];
    }
    $targetElement->_options = $filteredOptions;
  }
}

function fieldlookup_civicrm_custom($op, $groupId, $entityId, &$params) {
  if ($op == 'delete') {
    return;
  }

  // Check for reverse lookups.
  $customFieldIds = CRM_Utils_Array::collect('custom_field_id', $params);
  $customFieldNames = [];
  foreach ($customFieldIds as $fieldId) {
    $customFieldNames[] = 'custom_' . $fieldId;
  }
  $fieldLookupGroups = civicrm_api3('FieldLookupGroup', 'get', [
    'field_1_name' => ['IN' => $customFieldNames],
    'lookup_type' => "reverse",
    'options' => ['limit' => 0],
  ]);

  foreach ($fieldLookupGroups['values'] as $lookupGroup) {
    // If reverse lookups are found.
    doReverseLookup($lookupGroup, $params, $entityId);
  }
}

// Currently only supports custom fields!
function doReverseLookup($lookupGroup, $params, $entityId) {
  // Find the $params key that has the field1 info.
  $field1Name = $lookupGroup['field_1_name'];
  if (strpos($field1Name, 'custom_') === 0) {
    $customFieldId = substr($field1Name, 7);
  }
  $key = array_search($customFieldId, array_column($params, 'custom_field_id'));
  $field1Value = $params[$key]['value'];
  
  // Find the fieldLookup that matches.
  $fieldLookup = civicrm_api3('FieldLookup', 'get', [
    'sequential' => 1,
    'field_lookup_group_id' => $lookupGroup['id'],
    'field_1_value' => ['=' => $field1Value],
  ])['values'];
  setField2($lookupGroup, $entityId, $fieldLookup[0]['field_2_value']);
}

// Given a fieldLookupGroup record, entity ID and value, set the field 2 value.
function setField2($fieldLookup, $entityId, $field2Value) {
  $field2Name = $fieldLookup['field_2_name'];
  $field2Entity = $fieldLookup['field_2_entity'];
  // Fill in the reverse lookup here.
  civicrm_api3($field2Entity, 'create', [
    'id' => $entityId,
    $field2Name => $field2Value,
  ]);
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function fieldlookup_civicrm_config(&$config) {
  _fieldlookup_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function fieldlookup_civicrm_xmlMenu(&$files) {
  _fieldlookup_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function fieldlookup_civicrm_install() {
  _fieldlookup_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function fieldlookup_civicrm_postInstall() {
  _fieldlookup_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function fieldlookup_civicrm_uninstall() {
  _fieldlookup_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function fieldlookup_civicrm_enable() {
  _fieldlookup_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function fieldlookup_civicrm_disable() {
  _fieldlookup_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function fieldlookup_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _fieldlookup_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function fieldlookup_civicrm_managed(&$entities) {
  _fieldlookup_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function fieldlookup_civicrm_caseTypes(&$caseTypes) {
  _fieldlookup_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function fieldlookup_civicrm_angularModules(&$angularModules) {
  _fieldlookup_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function fieldlookup_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _fieldlookup_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function fieldlookup_civicrm_entityTypes(&$entityTypes) {
  _fieldlookup_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function fieldlookup_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function fieldlookup_civicrm_navigationMenu(&$menu) {
  _fieldlookup_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _fieldlookup_civix_navigationMenu($menu);
} // */
