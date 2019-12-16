<?php

/*
 * This file is part of Oveleon AdvancedFormBundle.
 *
 * (c) https://www.oveleon.de/
 */

// Back end modules
array_insert($GLOBALS['BE_MOD']['content'], 3, array
(
    'advanced_form' => array
    (
        'tables'            => array('tl_advanced_form', 'tl_advanced_form_page', 'tl_form_field', 'tl_advanced_form_data')
    )
));

// Front end modules
array_insert($GLOBALS['FE_MOD']['application'], 1, array
(
    'advancedForm'          => '\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedForm',
    'advancedFormData'      => '\\Oveleon\\ContaoAdvancedFormBundle\\ModuleAdvancedFormData',
));

// Content elements
array_insert($GLOBALS['TL_CTE']['includes'], 3, array
(
    'advancedForm'          => '\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedForm',
));

// Frontend form fields
array_insert($GLOBALS['TL_FFL'], -1, array
(
    'map'            => '\\Oveleon\\ContaoAdvancedFormBundle\\FormMapField',
    'range'          => '\\Oveleon\\ContaoAdvancedFormBundle\\FormRangeField',
    'usermail'       => '\\Oveleon\\ContaoAdvancedFormBundle\\FormUserMail',
    'username'       => '\\Oveleon\\ContaoAdvancedFormBundle\\FormUserName',
));

// Models
$GLOBALS['TL_MODELS']['tl_advanced_form']      = '\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedFormModel';
$GLOBALS['TL_MODELS']['tl_advanced_form_page'] = '\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedFormPageModel';
$GLOBALS['TL_MODELS']['tl_advanced_form_data'] = '\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedFormDataModel';
$GLOBALS['TL_MODELS']['tl_form_field']         = '\\Oveleon\\ContaoAdvancedFormBundle\\FormFieldModel';

// Hooks
$GLOBALS['TL_HOOKS']['compileFormFields'][] = array('\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedFormField', 'unsetAdvancedFormFields');
$GLOBALS['TL_HOOKS']['getPageLayout'][]     = array('\\Oveleon\\ContaoAdvancedFormBundle\\AdvancedFormField', 'removeModulesFromLayout');