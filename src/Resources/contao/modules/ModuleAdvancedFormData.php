<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;

/**
 * Front end module "advanced form data".
 *
 * @property integer $advancedForm
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class ModuleAdvancedFormData extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_advancedFormData';

    /**
     * Return a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            /** @var \BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['advancedFormData'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        if (isset($_GET['module']) && $_GET['module'] == $this->id)
        {
            $this->performAction();
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        $limit = null;
        $offset = (int) $this->skipFirst;

        // Maximum number of items
        if ($this->numberOfItems > 0)
        {
            $limit = $this->numberOfItems;
        }

        $this->Template->dataRows = array();

        // Get the total number of items
        $intTotal = $this->countItems();

        if ($intTotal < 1)
        {
            return;
        }

        $total = $intTotal - $offset;

        // Split the results
        if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
        {
            // Adjust the overall limit
            if (isset($limit))
            {
                $total = min($limit, $total);
            }

            // Get the current page
            $id = 'page_n' . $this->id;
            $page = (\Input::get($id) !== null) ? \Input::get($id) : 1;

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
            {
                throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));
            }

            // Set limit and offset
            $limit = $this->perPage;
            $offset += (max($page, 1) - 1) * $this->perPage;
            $skip = (int) $this->skipFirst;

            // Overall limit
            if ($offset + $limit > $total + $skip)
            {
                $limit = $total + $skip - $offset;
            }

            // Add the pagination menu
            $objPagination = new \Pagination($total, $this->perPage, \Config::get('maxPaginationLinks'), $id);
            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        $objAdvancedFormData = $this->fetchItems(($limit ?: 0), $offset);

        // Add the articles
        if ($objAdvancedFormData !== null)
        {
            $this->Template->advancedFormData = $this->parseAdvancedFormDataList($objAdvancedFormData);
        }
    }

    /**
     * Count the total matching items
     *
     * @return integer
     */
    protected function countItems()
    {
        switch ($this->advancedFormDataListMode)
        {
            case 'member_data':
                if (FE_USER_LOGGED_IN)
                {
                    $objUser = \FrontendUser::getInstance();
                    return AdvancedFormDataModel::countByMember($objUser->id);
                }
                break;

            case 'by_parameter':
                break;
        }

        return 0;
    }

    /**
     * Fetch the matching items
     *
     * @param integer $limit
     * @param integer $offset
     *
     * @return \Model\Collection|AdvancedFormDataModel|null
     */
    protected function fetchItems($limit, $offset)
    {
        switch ($this->advancedFormDataListMode)
        {
            case 'member_data':
                if (FE_USER_LOGGED_IN)
                {
                    $objUser = \FrontendUser::getInstance();
                    return AdvancedFormDataModel::findByMember($objUser->id, array('limit'=>$limit,'offset'=>$offset));
                }
                break;

            case 'by_parameter':
                break;
        }

        return null;
    }

    /**
     * Parse an item and return it as string
     *
     * @param AdvancedFormDataModel $objAdvancedFormData
     * @param string    $strClass
     * @param integer   $intCount
     *
     * @return string
     */
    protected function parseAdvancedFormData($objAdvancedFormData, $strClass='', $intCount=0)
    {
        /** @var \FrontendTemplate|object $objTemplate */
        $objTemplate = new \FrontendTemplate($this->advancedFormDataTemplate);
        $objTemplate->setData($objAdvancedFormData->row());

        if ($objAdvancedFormData->cssClass != '')
        {
            $strClass = ' ' . $objAdvancedFormData->cssClass . $strClass;
        }

        $objTemplate->class = $strClass;
        $objTemplate->dateAdded = \Date::parse(\Config::get('datimFormat'), $objAdvancedFormData->tstamp);
        $objTemplate->editLink = \Environment::get('base') . 'contaoadvancedform/' . $this->cteAlias . '/edit/' . $this->advancedFormModule . '/main-above/' . $objAdvancedFormData->id . '/start';
        $objTemplate->deleteLink = \Environment::get('request') . ((strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'module=' . $this->id . '&action=delete&id=' . $objAdvancedFormData->id;

        $objAdvancedForm = AdvancedFormModel::findByPk($this->advancedForm);

        if ($objAdvancedForm !== null)
        {
            $fieldMapping = \StringUtil::deserialize($objAdvancedForm->fieldMapping, true);

            if (count($fieldMapping))
            {
                foreach ($fieldMapping as $mapping)
                {
                    if (isset($objAdvancedFormData->{$mapping['label']}))
                    {
                        $objTemplate->{$mapping['value']} = $objAdvancedFormData->{$mapping['label']};
                    }
                }
            }
        }

        return $objTemplate->parse();
    }

    /**
     * Parse one or more items and return them as array
     *
     * @param \Model\Collection $objAdvancedFormData
     *
     * @return array
     */
    protected function parseAdvancedFormDataList($objAdvancedFormData)
    {
        $limit = $objAdvancedFormData->count();

        if ($limit < 1)
        {
            return array();
        }

        $count = 0;
        $arrAdvancedFormData = array();

        while ($objAdvancedFormData->next())
        {
            /** @var AdvancedFormDataModel $objData */
            $objData = $objAdvancedFormData->current();

            $arrAdvancedFormData[] = $this->parseAdvancedFormData($objData, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
        }

        return $arrAdvancedFormData;
    }

    /**
     * Perform an action to delete or edit advance form data
     */
    protected function performAction()
    {
        if (!FE_USER_LOGGED_IN || !isset($_GET['action']) || !isset($_GET['id']))
        {
            return;
        }

        $objUser = \FrontendUser::getInstance();

        $advancedFormData = AdvancedFormDataModel::findByIdAndMember($_GET['id'], $objUser->id);

        if ($advancedFormData === null)
        {
            return;
        }

        switch ($_GET['action'])
        {
            case 'delete':
                $advancedFormData->delete();
                break;
            case 'edit':

                break;
        }
    }
}