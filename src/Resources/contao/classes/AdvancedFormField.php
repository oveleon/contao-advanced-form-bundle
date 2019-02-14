<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;


class AdvancedFormField
{
    /**
     * @param $arrFields
     * @param $formId
     * @param $form
     *
     * @return array
     */
    public function unsetAdvancedFormFields($arrFields, $formId, $form)
    {
        if (get_class($form) !== 'Oveleon\ContaoAdvancedFormBundle\AdvancedForm')
        {
            foreach ($arrFields as $key => $field)
            {
                if ($field->ptable !== 'tl_form')
                {
                    unset($arrFields[$key]);
                }
            }
        }

        return $arrFields;
    }

    /**
     * @param \PageModel   $objPage
     * @param \LayoutModel $objLayout
     * @param \PageRegular $pageRegular
     */
    public function removeModulesFromLayout(&$objPage, &$objLayout, $pageRegular)
    {
        if (\Environment::get('AdvancedForm'))
        {
            $modules = array
            (
                array
                (
                    'mod'    => \Environment::get('AdvancedFormModule'),
                    'col'    => \Environment::get('AdvancedFormColumn'),
                    'enable' => 1
                )
            );

            $objLayout->modules = serialize($modules);
            $objPage->cssClass .= ' modal';
        }
    }
}