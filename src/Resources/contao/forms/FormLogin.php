<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Class FormLogin
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
class FormLogin extends \Widget
{

    /**
     * Submit user input
     *
     * @var boolean
     */
    protected $blnSubmitInput = false;

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
    protected $strTemplate = 'form_login';

    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-login';

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
            default:
                return parent::__get($strKey);
                break;
        }
    }

    /**
     * Set regular validation
     *
     * @return mixed The validated user input
     */
    public function validate()
    {
        if (empty($this->getPost('username')) || empty($this->getPost('password')))
        {
            $this->addError($GLOBALS['TL_LANG']['MSC']['emptyField']);
            $this->message = $GLOBALS['TL_LANG']['MSC']['emptyField'];

            return;
        }

        $this->import('FrontendUser', 'User');

        // Login and redirect
        if ($this->User->login())
        {
            $_SESSION['ADVANCED_FORM_GUEST_PAGE_VISIBLE'] = true;
        }
        else
        {
            $this->addError($GLOBALS['TL_LANG']['ERR']['invalidLogin']);
            $this->message = $GLOBALS['TL_LANG']['ERR']['invalidLogin'];
        }

        if ($this->hasErrors())
        {
            $this->class = 'error';
        }
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string The widget markup
     */
    public function generate()
    {
        return 'FormLogin';
    }
}