<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Class FormUserMail
 *
 * @property string  $value
 * @property string  $type
 * @property integer $maxlength
 * @property boolean $mandatory
 * @property integer $min
 * @property integer $max
 * @property integer $step
 * @property string  $placeholder
 * @property boolean $hideInput
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class FormUserMail extends \Widget
{
    /**
     * Submit user input
     *
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Add a for attribute
     *
     * @var boolean
     */
    protected $blnForAttribute = true;

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_usermail';

    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-text';

    /**
     * Add specific attributes
     *
     * @param string $strKey   The attribute key
     * @param mixed  $varValue The attribute value
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'maxlength':
                if ($varValue > 0)
                {
                    $this->arrAttributes['maxlength'] =  $varValue;
                }
                break;

            case 'mandatory':
                if ($varValue)
                {
                    $this->arrAttributes['required'] = 'required';
                }
                else
                {
                    unset($this->arrAttributes['required']);
                }
                parent::__set($strKey, $varValue);
                break;

            case 'min':
            case 'max':
            case 'step':
            case 'placeholder':
                $this->arrAttributes[$strKey] = $varValue;
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Return a parameter
     *
     * @param string $strKey The parameter key
     *
     * @return mixed The parameter value
     */
    public function __get($strKey)
    {
        switch ($strKey)
        {
            case 'value':
                return \Idna::decodeEmail($this->varValue);
                break;

            default:
                return parent::__get($strKey);
                break;
        }
    }

    /**
     * Trim the values
     *
     * @param mixed $varInput The user input
     *
     * @return mixed The validated user input
     */
    protected function validator($varInput)
    {
        $this->rgxp = 'email';

        if (\is_array($varInput))
        {
            return parent::validator($varInput);
        }

        $varInput = \Idna::encodeEmail($varInput);

        $this->import('Database');

        $objMember = $this->Database->prepare("SELECT id FROM tl_member WHERE email=?")
                                    ->execute($varInput);

        if ($objMember->numRows > 0)
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['mailAssigned']);
        }

        return parent::validator($varInput);
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string The widget markup
     */
    public function generate()
    {
        return sprintf('<input type="email" name="%s" id="ctrl_%s" class="text%s" value="%s"%s%s',
            $this->strName,
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            \StringUtil::specialchars($this->value),
            $this->getAttributes(),
            $this->strTagEnding);
    }
}