<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_advanced_form_data'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_advanced_form',
        'enableVersioning'            => true,
        'markAsCopy'                  => 'title',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('tstamp DESC', 'title DESC'),
            'headerFields'            => array('title', 'tstamp'),
            'panelLayout'             => 'filter;sort,search,limit',
            'child_record_callback'   => array('tl_advanced_form_data', 'listFormData'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},title,member;{textfield_legend},textfield1,textfield2,textfield3,textfield4,textfield5,textfield6;{numberfield_legend},numberfield1,numberfield2,numberfield3,numberfield4,numberfield5,numberfield6;{teaxtarea_legend},textarea1,textarea2,textarea3'
    ),


    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'foreignKey'              => 'tl_advanced_form.title',
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
        ),
        'tstamp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['tstamp'],
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 6,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'dateAdded' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['dateAdded'],
            'sorting'                 => true,
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'rawData' => array
        (
            'sql'                     => "blob NULL"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'member' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['member'],
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'textfield1' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield1'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'textfield2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield2'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'textfield3' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield3'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'textfield4' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield4'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'textfield5' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield5'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'textfield6' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textfield6'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'numberfield1' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield1'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'numberfield2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield2'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'numberfield3' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield3'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'numberfield4' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield4'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'numberfield5' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield5'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'numberfield6' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['numberfield6'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned default NULL",
        ),
        'textarea1' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textarea1'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ),
        'textarea2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textarea2'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ),
        'textarea3' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_data']['textarea3'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ),
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_advanced_form_data extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * List an advanced form dataset
     *
     * @param array $arrRow
     *
     * @return string
     */
    public function listFormData($arrRow)
    {
        return '<div class="tl_content_left"><span style="color:#999;padding-left:3px">[' . \Date::parse(\Config::get('datimFormat'), $arrRow['tstamp']) . ']</span> ' . $arrRow['title'] . '</div>';
    }
}