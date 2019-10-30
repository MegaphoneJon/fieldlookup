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
    $fieldName = CRM_Utils_Request::retrieve('fieldname', 'String');
    $value = CRM_Utils_Request::retrieve('value', 'String');
    $output = [
      1 => "$fieldName",
      2 => "$value",
    ];
    return CRM_Utils_JSON::output($output);
  }

}
