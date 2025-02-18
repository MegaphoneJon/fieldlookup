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
  // We have an issue where this is called before the autoloader can find the FieldLookupGroup API, so we look using the DAO.
  $dao = new CRM_Fieldlookup_DAO_FieldLookupGroup();
  $dao->find();
  while ($dao->fetch()) {
    $items["civicrm/ajax/chainselect/{$dao->field_2_name}"] = [
      'page_callback' => 'CRM_Fieldlookup_AJAX::chainSelectJSON',
    ];
  }
}

function fieldlookup_civicrm_buildForm($formName, &$form) {
  // For now, we're just working on multi-record custom field forms.  Will expand this later.
  if ($formName != 'CRM_Contact_Form_CustomData') {
    return;
  }
  // We no longer use this JS - will remove at a later date.
  // CRM_Core_Resources::singleton()->addScriptFile('fieldlookup', 'js/fieldlookup.js');
  // Collect a list of Select fields, so we can check for field lookups.
  foreach ($form->_elements as $element) {
    if ($element instanceof HTML_QuickForm_select) {
      preg_match('/(custom_\d+)/', $element->_attributes['name'], $matches);
      $selectFields[$matches[1]] = $element->_attributes['name'];
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
  $lookupGroups = [];
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
 */
function fieldlookup_addChainSelect($elementName, array $settings, &$form) {
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
    // We can't use data-api-field since you need "Administer CiviCRM" so let's calculate it with preg_match.
    preg_match('/(custom_\d+)/', $targetElement->_attributes['name'], $matches);
    $targetField = $matches[1];
    $options = CRM_Fieldlookup_AJAX::chainSelect($targetField, $form->_defaultValues[$settings['control-field-name']] ?? FALSE);
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

function fieldlookup_civicrm_post_callback(Civi\Core\Event\PostEvent $event) {
  $op = $event->action;
  $objectName = $event->entity;
  $id = $event->id;
  $object = $event->object;
  // Some entities have a post hook but no API, that's bad news.  Skip them.
  $validEntities = CRM_Fieldlookup_SelectValues::getEntities();
  if (!in_array($objectName, $validEntities)) {
    return;
  }
  if (CRM_Core_Transaction::isActive()) {
    CRM_Core_Transaction::addPostCommit($event);
  }
  else {
    findNoncustomFieldReverseLookups($op, $objectName, $id, $object);
  }
}

/**
 * Identifies reverse lookups triggered by non-custom fields.
 */
function findNoncustomFieldReverseLookups($op, $objectName, $id, $object) {
  if ($op == 'delete') {
    return;
  }
  // Check for reverse lookups.
  $fields = array_keys((array) $object);
  $fieldLookupGroups = CRM_Fieldlookup_BAO_FieldLookup::getReverseLookupGroups($fields, $objectName);
  foreach ($fieldLookupGroups as $lookupGroup) {
    // If reverse lookups are found.
    // Handle different foreign keys on the other table.
    if ($lookupGroup['table_1_fk'] ?? FALSE) {
      $foreignKey = $lookupGroup['table_1_fk'];
      $entityId = $object->$foreignKey;
      // If a foreign key is specified but missing, we're not doing any field lookups. E.g. for addresses in a LocBlock.
      if (!$entityId) {
        return;
      }
    }
    else {
      $entityId = $id;
    }
    $field1Name = $lookupGroup['field_1_name'];
    $field1Value = $object->$field1Name;
    if ($field1Value) {
      doReverseLookup($lookupGroup, $field1Value, $entityId);
    }
  }
}

function fieldlookup_civicrm_custom_callback(Civi\Core\Event\GenericHookEvent $event) {
  $values = $event->getHookValues();
  $op = $values[0];
  $groupId = $values[1];
  $entityId = $values[2];
  $params = $values[3];
  if (CRM_Core_Transaction::isActive()) {
    CRM_Core_Transaction::addCallback(CRM_Core_Transaction::PHASE_POST_COMMIT, 'findCustomFieldReverseLookups', [$op, $groupId, $entityId, &$params]);
  }
  else {
    findCustomFieldReverseLookups($op, $groupId, $entityId, $params);
  }
}

/**
 * Identifies reverse lookups triggered by custom fields.
 */
function findCustomFieldReverseLookups($op, $groupId, $entityId, $params) {
  if ($op == 'delete') {
    return;
  }

  // Check for reverse lookups.
  $customFieldIds = CRM_Utils_Array::collect('custom_field_id', $params);
  $customFieldNames = [];
  foreach ($customFieldIds as $fieldId) {
    $customFieldNames[] = 'custom_' . $fieldId;
  }
  $fieldLookupGroups = CRM_Fieldlookup_BAO_FieldLookup::getReverseLookupGroups($customFieldNames);

  foreach ($fieldLookupGroups as $lookupGroup) {
    // If reverse lookups are found.
    $field1Name = $lookupGroup['field_1_name'];
    if (strpos($field1Name, 'custom_') === 0) {
      $customFieldId = substr($field1Name, 7);
    }
    $key = array_search($customFieldId, array_column($params, 'custom_field_id'));
    $field1Value = $params[$key]['value'];
    doReverseLookup($lookupGroup, $field1Value, $entityId);
  }
}

/**
 * Handles reverse lookups.
 *
 * @param $lookupGroup array
 * @param $field1Value string
 * @param $entityId int
 */
function doReverseLookup($lookupGroup, $field1Value, $entityId) {
  // Find the fieldLookup that matches.
  $params = [
    'sequential' => 1,
    'field_lookup_group_id' => $lookupGroup['id'],
    'field_1_value' => [$lookupGroup['lookup_operator'] => $field1Value],
  ];

  // Handle the "reverse between" case.
  if ($lookupGroup['lookup_operator'] == 'BETWEEN') {
    $params['field_1_value'] = ['<=' => $field1Value];
    $params['field_1_value_2'] = ['>=' => $field1Value];
  }

  $fieldLookup = civicrm_api3('FieldLookup', 'get', $params)['values'];
  if ($fieldLookup) {
    setField2($lookupGroup, $entityId, $fieldLookup[0]['field_2_value']);
  }
}

/**
 * Given a fieldLookupGroup record, entity ID and value, set the field 2 value.
 */
function setField2($fieldLookup, $entityId, $field2Value) {
  $field2Name = $fieldLookup['field_2_name'];
  $field2Entity = $fieldLookup['field_2_entity'];
  // Check to see if the field has the same value; if so we won't overwrite it.
  $existingValue = civicrm_api3($field2Entity, 'get', [
    'sequential' => 1,
    'return' => [$field2Name],
    'id' => $entityId,
  ])['values'][0][$field2Name] ?? NULL;
  if ($existingValue !== $field2Value) {
    // Fill in the reverse lookup here.
    civicrm_api4($field2Entity, 'update', [
      'values' => [
        $field2Name => $field2Value,
      ],
      'where' => [
        ['id', '=', $entityId],
      ],
      'checkPermissions' => FALSE,
      'fixAddress' => FALSE,
    ]);
  }

}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function fieldlookup_civicrm_config(&$config) {
  _fieldlookup_civix_civicrm_config($config);
  if (isset(Civi::$statics[__FUNCTION__])) {
    return;
  }
  Civi::$statics[__FUNCTION__] = 1;
  // We want to run last, to avoid unpleasant interactions with other extensions using the post hook.
  Civi::dispatcher()->addListener('hook_civicrm_post', 'fieldlookup_civicrm_post_callback', -10);
  Civi::dispatcher()->addListener('hook_civicrm_custom', 'fieldlookup_civicrm_custom_callback', -10);
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
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function fieldlookup_civicrm_enable() {
  _fieldlookup_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

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
