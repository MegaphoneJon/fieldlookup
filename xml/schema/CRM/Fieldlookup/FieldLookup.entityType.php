<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference
return [
  0 =>
  ['name' => 'FieldLookupGroup',
    'class' => 'CRM_Fieldlookup_DAO_FieldLookupGroup',
    'table' => 'civicrm_field_lookup_group',
  ],
  1 =>
  ['name' => 'FieldLookup',
    'class' => 'CRM_Fieldlookup_DAO_FieldLookup',
    'table' => 'civicrm_field_lookup',
  ],
];
