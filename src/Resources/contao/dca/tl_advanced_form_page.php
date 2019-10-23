<?php

/*
 * This file is part of Oveleon AdvancedFormBundle.
 *
 * (c) https://www.oveleon.de/
 */

$GLOBALS['TL_DCA']['tl_advanced_form_page'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_advanced_form',
        'ctable'                      => array('tl_form_field'),
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'markAsCopy'                  => 'title',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'index',
                'pid,start,stop,published' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 4,
            'fields'                  => array('sorting'),
            'headerFields'            => array('title', 'tstamp', 'formID'),
            'panelLayout'             => 'filter,search,limit',
            'child_record_callback'   => array('tl_advanced_form_page', 'listFormPages'),
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
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['edit'],
                'href'                => 'table=tl_form_field',
                'icon'                => 'edit.svg'
            ),
            'editheader' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.svg'
            ),
            'cut' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['toggle'],
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_advanced_form_page', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('sendViaEmail'),
        'default'                     => '{title_legend},title,alias;{condition_legend:hide},andConditions,conditions,guests,editMode;{email_legend},sendViaEmail;{store_legend:hide},storeValues;{template_legend:hide},customTpl;{expert_legend:hide},cssID,buttonLabel,editButtonLabel,hidePrevButton,clearData;{publish_legend},published,start,stop'
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'sendViaEmail'                => 'recipient,subject,format,skipEmpty',
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
        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['alias'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array
            (
                array('tl_advanced_form_page', 'generateAlias')
            ),
            'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
        ),
        'andConditions' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['andConditions'],
            'exclude'                 => true,
            'inputType'               => 'keyValueWizard',
            'sql'                     => "text NULL"
        ),
        'conditions' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['conditions'],
            'exclude'                 => true,
            'inputType'               => 'keyValueWizard',
            'sql'                     => "text NULL"
        ),
        'guests' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['guests'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'editMode' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['editMode'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'sendViaEmail' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['sendViaEmail'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'recipient' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['recipient'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>1022, 'rgxp'=>'emails', 'tl_class'=>'w50'),
            'sql'                     => "varchar(1022) NOT NULL default ''"
        ),
        'subject' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['subject'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'format' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['format'],
            'default'                 => 'raw',
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => array('raw', 'xml', 'csv', 'email'),
            'reference'               => &$GLOBALS['TL_LANG']['tl_advanced_form_page'],
            'eval'                    => array('helpwizard'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(12) NOT NULL default ''"
        ),
        'skipEmpty' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['skipEmtpy'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'storeValues' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['storeValues'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'customTpl' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['customTpl'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_advanced_form_page', 'getFormWrapperTemplates'),
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'cssID' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['cssID'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('multiple'=>true, 'size'=>2, 'tl_class'=>'w50 clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'buttonLabel' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['buttonLabel'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'editButtonLabel' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['editButtonLabel'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'hidePrevButton' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['hidePrevButton'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'clearData' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['clearData'],
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'start' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['start'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_advanced_form_page']['stop'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_advanced_form_page extends Backend
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
     * Auto-generate the advanced form page alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '')
        {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->title);
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_advanced_form_page WHERE alias=? AND id!=?")
            ->execute($varValue, $dc->id);

        // Check whether the news alias exists
        if ($objAlias->numRows)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * List an advanced form page
     *
     * @param array $arrRow
     *
     * @return string
     */
    public function listFormPages($arrRow)
    {
        $arrConditions = \StringUtil::deserialize($arrRow['conditions'], true);
        $strCondition = count($arrConditions) ? '(' : '';

        foreach ($arrConditions as $i => $condition)
        {
            $strCondition .= $condition['key'] . ' == ' . $condition['value'];

            if (count($arrConditions) === ($i+1))
            {
                $strCondition .= ')';
            }
            else
            {
                $strCondition .= ' || ';
            }
        }

        return '<div class="tl_content_left">' . $arrRow['title'] . ' <span style="color:#999;padding-left:3px">' . $strCondition . '</span></div>';
    }

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (\strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_advanced_form_page::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.svg';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }

    /**
     * Disable/enable an advanced form page
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        // Trigger the onload_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_advanced_form_page']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_advanced_form_page']['config']['onload_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        // Check the field access
        if (!$this->User->hasAccess('tl_advanced_form_page::published', 'alexf'))
        {
            throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish news item ID ' . $intId . '.');
        }

        // Set the current record
        if ($dc)
        {
            $objRow = $this->Database->prepare("SELECT * FROM tl_advanced_form_page WHERE id=?")
                ->limit(1)
                ->execute($intId);

            if ($objRow->numRows)
            {
                $dc->activeRecord = $objRow;
            }
        }

        $objVersions = new Versions('tl_advanced_form_page', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_advanced_form_page']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_advanced_form_page']['fields']['published']['save_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (\is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_advanced_form_page SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
            ->execute($intId);

        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

        // Trigger the onsubmit_callback
        if (\is_array($GLOBALS['TL_DCA']['tl_advanced_form_page']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_advanced_form_page']['config']['onsubmit_callback'] as $callback)
            {
                if (\is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (\is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }

        $objVersions->create();
    }

    /**
     * Return all form wrapper templates as array
     *
     * @return array
     */
    public function getFormWrapperTemplates()
    {
        return $this->getTemplateGroup('advanced_form_wrapper');
    }

    /**
     * Get all tables and return them as array
     *
     * @return array
     */
    public function getAllTables()
    {
        return $this->Database->listTables();
    }
}