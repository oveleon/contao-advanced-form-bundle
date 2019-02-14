<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */


namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Class FormTextField
 *
 * @property string  $value
 * @property boolean $mandatory
 * @property integer $min
 * @property integer $max
 * @property integer $step
 *
 * @author Daniele Sciannimanica <daniele@oveleon.de>
 */
class FormRangeField extends \Widget
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
	protected $strTemplate = 'form_rangefield';

	/**
	 * The CSS class prefix
	 *
	 * @var string
	 */
	protected $strPrefix = 'widget widget-range';

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

            case 'outputField':
                $this->outputField = $varValue;
                break;
            case 'min':
            case 'rangemin':
                $this->arrAttributes['min'] = $varValue;
                break;
            case 'max':
            case 'rangemax':
                $this->arrAttributes['max'] = $varValue;
                break;
			case 'step':
			case 'rangestep':
			    $this->arrAttributes['step'] = $varValue;
                break;
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}



    /**
     * Set regular validation
     *
     * @param mixed $varInput The user input
     *
     * @return mixed The validated user input
     */
    protected function validator($varInput)
    {
        $this->rgxp = 'digit';

        return $varInput;

        return parent::validator($varInput);
    }

	/**
	 * Generate the widget and return it as string
	 *
	 * @return string The widget markup
	 */
	public function generate()
	{
		return sprintf('<input type="range" name="%s" id="ctrl_%s" class="range%s" value="%s"%s%s',
						$this->strName,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						\StringUtil::specialchars($this->value),
						$this->getAttributes(),
						$this->strTagEnding);
	}
}

class_alias(FormRangeField::class, 'FormRangeField');
