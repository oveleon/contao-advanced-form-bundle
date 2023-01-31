<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_advanced_form'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_advanced_form_page'),
        'switchToEdit'                => true,
        'enableVersioning'            => true,
		'markAsCopy'                  => 'title',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('title', 'formID'),
			'format'                  => '%s <span style="color:#999;padding-left:3px">[%s]</span>'
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
				'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['edit'],
				'href'                => 'table=tl_advanced_form_page',
				'icon'                => 'edit.svg'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.svg'
			),
            'editdata' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['editdata'],
                'href'                => 'table=tl_advanced_form_data',
                'icon'                => 'db.svg'
            ),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.svg'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
        '__selector__'                => array('storeValues'),
		'default'                     => '{title_legend},title,alias,jumpTo;{config_legend},allowTags;{store_legend:hide},storeValues;{expert_legend:hide},method,novalidate,attributes,formID'
	),

    // Subpalettes
    'subpalettes' => array
    (
        'storeValues'                 => 'fieldMapping'
    ),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_advanced_form', 'generateAlias')
			),
			'sql'                     => "varchar(128) BINARY NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
        'storeValues' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['storeValues'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'fieldMapping' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['fieldMapping'],
            'exclude'                 => true,
            'inputType'               => 'optionWizard',
            'xlabel' => array
            (
                array('tl_advanced_form', 'optionImportWizard')
            ),
            'sql'                     => "blob NULL"
        ),
		'method' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['method'],
			'default'                 => 'POST',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('POST', 'GET'),
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(12) NOT NULL default ''"
		),
		'novalidate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['novalidate'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'attributes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['attributes'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'formID' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['formID'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>true, 'doNotCopy'=>true, 'maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'allowTags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form']['allowTags'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_advanced_form extends Backend
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
	 * Auto-generate an advanced form alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
        $autoAlias = false;

        // Generate an alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->title);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_advanced_form WHERE id=? OR alias=?")
            ->execute($dc->id, $varValue);

        // Check whether the form alias exists
        if ($objAlias->numRows > 1)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf(($GLOBALS['TL_LANG']['ERR']['aliasExists'] ?? null), $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
	}

    /**
     * Add a link to the option items import wizard
     *
     * @return string
     */
    public function optionImportWizard()
    {
        return ' <a href="' . $this->addToUrl('key=option') . '" title="' . StringUtil::specialchars(($GLOBALS['TL_LANG']['MSC']['ow_import'][1] ?? '')) . '" onclick="Backend.getScrollOffset()">' . Image::getHtml('tablewizard.svg', ($GLOBALS['TL_LANG']['MSC']['ow_import'][0] ?? '')) . '</a>';
    }
}
