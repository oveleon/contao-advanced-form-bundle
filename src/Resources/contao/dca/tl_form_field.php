<?php

use Contao\ArrayUtil;

$GLOBALS['TL_DCA']['tl_form_field']['config']['dynamicPtable'] = true;

// Dynamically add the permission check and parent table
if (Input::get('do') == 'advanced_form')
{
    $GLOBALS['TL_DCA']['tl_form_field']['config']['ptable'] = 'tl_advanced_form_page';
}

$GLOBALS['TL_DCA']['tl_form_field']['fields']['ptable']['sql'] = "varchar(64) NOT NULL default 'tl_form'";

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['login']     = '{type_legend},type;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['map']       = '{type_legend},type,name,label;{expert_legend:hide},class,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['range']     = '{type_legend},type,name,label;{fconfig_legend},mandatory,outputField,outputLegend;{expert_legend:hide},class,value,rangemin,rangemax,rangestep,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['usermail']  = '{type_legend},type,name,label;{fconfig_legend},mandatory,placeholder;{expert_legend:hide},class,value,minlength,maxlength,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['username']  = '{type_legend},type,name,label;{fconfig_legend},mandatory,placeholder;{expert_legend:hide},class,value,minlength,maxlength,accesskey,tabindex;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';

// Add fields
ArrayUtil::arrayInsert($GLOBALS['TL_DCA']['tl_form_field']['fields'], 0, array
(
    'rangemin' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rangemin'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ),
    'rangemax' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rangemax'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
        'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ),
    'rangestep' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['rangestep'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true),
        'sql'                     => "int(10) unsigned NOT NULL default '1'"
    ),
    'outputField' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['outputField'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('tl_class'=>'w50'),
        'sql'                     => "char(1) NOT NULL default ''"
    ),
    'outputLegend' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['outputLegend'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('tl_class'=>'w50'),
        'sql'                     => "char(1) NOT NULL default ''"
    ),
));



