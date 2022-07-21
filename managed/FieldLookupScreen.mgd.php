<?php
    return [
      [
        'name' => 'SavedSearch_Field_Lookups',
        'entity' => 'SavedSearch',
        'cleanup' => 'unused',
        'update' => 'always',
        'params' => [
          'version' => 4,
          'values' => [
            'name' => 'Field_Lookups',
            'label' => 'Field Lookups',
            'form_values' => NULL,
            'search_custom_id' => NULL,
            'api_entity' => 'FieldLookup',
            'api_params' => [
              'version' => 4,
              'select' => [
                'field_1_value',
                'field_1_value_2',
                'field_2_value',
                'field_lookup_group_id',
              ],
              'orderBy' => [],
              'where' => [],
              'groupBy' => [],
              'join' => [],
              'having' => [],
            ],
            'expires_date' => NULL,
            'description' => NULL,
            'mapping_id' => NULL,
          ],
        ],
      ],
      [
        'name' => 'SavedSearch_Field_Lookups_SearchDisplay_Field_Lookups_Table_1',
        'entity' => 'SearchDisplay',
        'cleanup' => 'unused',
        'update' => 'always',
        'params' => [
          'version' => 4,
          'values' => [
            'name' => 'Field_Lookups_Table_1',
            'label' => 'Field Lookups Table 1',
            'saved_search_id.name' => 'Field_Lookups',
            'type' => 'table',
            'settings' => [
              'actions' => TRUE,
              'limit' => 25,
              'classes' => [
                'table',
                'table-striped',
              ],
              'pager' => [],
              'placeholder' => 5,
              'sort' => [
                [
                  'id',
                  'ASC',
                ],
              ],
              'columns' => [
                [
                  'type' => 'field',
                  'key' => 'field_1_value',
                  'dataType' => 'String',
                  'label' => 'Field 1 Value',
                  'sortable' => TRUE,
                  'editable' => TRUE,
                ],
                [
                  'type' => 'field',
                  'key' => 'field_1_value_2',
                  'dataType' => 'String',
                  'label' => 'Field 1 Value 2',
                  'sortable' => TRUE,
                  'editable' => TRUE,
                ],
                [
                  'type' => 'field',
                  'key' => 'field_2_value',
                  'dataType' => 'String',
                  'label' => 'Field 2 Value',
                  'sortable' => TRUE,
                  'editable' => TRUE,
                ],
              ],
            ],
            'acl_bypass' => FALSE,
          ],
        ],
      ],
    ];
