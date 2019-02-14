<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['advancedForm'] = '{type_legend},type,headline;{include_legend},advancedForm;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['advancedForm'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['advancedForm'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_advanced_form.title',
    'options_callback'        => array('tl_content_advanced_form', 'getAdvancedForms'),
    'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50 wizard'),
    'wizard' => array
    (
        array('tl_content_advanced_form', 'editAdvancedForm')
    ),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_content_advanced_form extends \Backend
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
     * Get all advanced forms and return them as array
     *
     * @return array
     */
    public function getAdvancedForms()
    {
        if (!$this->User->isAdmin && !\is_array($this->User->forms))
        {
            return array();
        }

        $arrAdvancedForms = array();
        $objAdvancedForms = $this->Database->execute("SELECT id, title FROM tl_advanced_form ORDER BY title");

        while ($objAdvancedForms->next())
        {
            if ($this->User->hasAccess($objAdvancedForms->id, 'forms'))
            {
                $arrAdvancedForms[$objAdvancedForms->id] = $objAdvancedForms->title;
            }
        }

        return $arrAdvancedForms;
    }

    /**
     * Return the edit advanced form wizard
     *
     * @param DataContainer $dc
     *
     * @return string
     */
    public function editAdvancedForm(DataContainer $dc)
    {
        return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=advanced_form&amp;table=tl_advanced_form_page&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_content']['editalias'][0]) . '</a>';
    }
}