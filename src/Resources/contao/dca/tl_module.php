<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['advancedForm']     = '{title_legend},name,headline,type;{config_legend},advancedForm;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['advancedFormData'] = '{title_legend},name,headline,type;{config_legend},advancedForm,numberOfItems,perPage,skipFirst,cteAlias,advancedFormModule;{advanced_form_data_config_legend},advancedFormDataListMode;{redirect_legend},jumpTo;{template_legend:hide},advancedFormDataTemplate,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['advancedForm'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['advancedForm'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_advanced_form.title',
    'options_callback'        => array('tl_module_advanced_form', 'getAdvancedForms'),
    'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50 wizard'),
    'wizard' => array
    (
        array('tl_module_advanced_form', 'editAdvancedForm')
    ),
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['cteAlias'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['cteAlias'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_advanced_form', 'getAlias'),
    'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard'),
    'wizard' => array
    (
        array('tl_module_advanced_form', 'editAlias')
    ),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['advancedFormModule'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['advancedFormModule'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_advanced_form', 'getModules'),
    'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50 wizard'),
    'wizard' => array
    (
        array('tl_module_advanced_form', 'editModule')
    ),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['advancedFormDataTemplate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['advancedFormDataTemplate'],
    'default'                 => 'advanced_form_data_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_advanced_form', 'getAdvancedFormDataTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['advancedFormDataListMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['advancedFormDataListMode'],
    'default'                 => 'member_data',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('member_data', 'by_parameter'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(16) NOT NULL default ''"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class tl_module_advanced_form extends \Backend
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

    /**
     * Return all advanced form data templates as array
     *
     * @return array
     */
    public function getAdvancedFormDataTemplates()
    {
        return $this->getTemplateGroup('advanced_form_data_');
    }

    /**
     * Return the edit alias wizard
     *
     * @param DataContainer $dc
     *
     * @return string
     */
    public function editAlias(DataContainer $dc)
    {
        return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=article&amp;table=tl_content&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_content']['editalias'][0]) . '</a>';
    }

    /**
     * Get all content elements and return them as array (content element alias)
     *
     * @return array
     */
    public function getAlias()
    {
        $arrPids = array();
        $arrAlias = array();

        if (!$this->User->isAdmin)
        {
            foreach ($this->User->pagemounts as $id)
            {
                $arrPids[] = $id;
                $arrPids = array_merge($arrPids, $this->Database->getChildRecords($id, 'tl_page'));
            }

            if (empty($arrPids))
            {
                return $arrAlias;
            }

            $objAlias = $this->Database->prepare("SELECT c.id, c.pid, c.type, (CASE c.type WHEN 'module' THEN m.name WHEN 'form' THEN f.title WHEN 'table' THEN c.summary ELSE c.headline END) AS headline, c.text, a.title FROM tl_content c LEFT JOIN tl_article a ON a.id=c.pid LEFT JOIN tl_module m ON m.id=c.module LEFT JOIN tl_form f on f.id=c.form WHERE a.pid IN(". implode(',', array_map('\intval', array_unique($arrPids))) .") AND (c.ptable='tl_article' OR c.ptable='') AND c.id!=? ORDER BY a.title, c.sorting")
                ->execute(Input::get('id'));
        }
        else
        {
            $objAlias = $this->Database->prepare("SELECT c.id, c.pid, c.type, (CASE c.type WHEN 'module' THEN m.name WHEN 'form' THEN f.title WHEN 'table' THEN c.summary ELSE c.headline END) AS headline, c.text, a.title FROM tl_content c LEFT JOIN tl_article a ON a.id=c.pid LEFT JOIN tl_module m ON m.id=c.module LEFT JOIN tl_form f on f.id=c.form WHERE (c.ptable='tl_article' OR c.ptable='') AND c.id!=? ORDER BY a.title, c.sorting")
                ->execute(Input::get('id'));
        }

        while ($objAlias->next())
        {
            $arrHeadline = StringUtil::deserialize($objAlias->headline, true);

            if (isset($arrHeadline['value']))
            {
                $headline = StringUtil::substr($arrHeadline['value'], 32);
            }
            else
            {
                $headline = StringUtil::substr(preg_replace('/[\n\r\t]+/', ' ', $arrHeadline[0]), 32);
            }

            $text = StringUtil::substr(strip_tags(preg_replace('/[\n\r\t]+/', ' ', $objAlias->text)), 32);
            $strText = $GLOBALS['TL_LANG']['CTE'][$objAlias->type][0] . ' (';

            if ($headline != '')
            {
                $strText .= $headline . ', ';
            }
            elseif ($text != '')
            {
                $strText .= $text . ', ';
            }

            $key = $objAlias->title . ' (ID ' . $objAlias->pid . ')';
            $arrAlias[$key][$objAlias->id] = $strText . 'ID ' . $objAlias->id . ')';
        }

        return $arrAlias;
    }

    /**
     * Return the edit module wizard
     *
     * @param DataContainer $dc
     *
     * @return string
     */
    public function editModule(DataContainer $dc)
    {
        return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_content']['editalias'][1]), $dc->value) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_content']['editalias'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_content']['editalias'][0]) . '</a>';
    }

    /**
     * Get all modules and return them as array
     *
     * @return array
     */
    public function getModules()
    {
        $arrModules = array();
        $objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id ORDER BY t.name, m.name");

        while ($objModules->next())
        {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }
}