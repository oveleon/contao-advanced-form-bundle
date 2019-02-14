<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Reads and writes form fields
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class FormFieldModel extends \FormFieldModel
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_form_field';

	/**
	 * Find published form fields by their parent ID
	 *
	 * @param integer $intPid     The form ID
	 * @param string  $ptable     Parent table
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|FormFieldModel[]|FormFieldModel|null A collection of models or null if there are no form fields
	 */
	public static function findPublishedByPid($intPid, $ptable='tl_form', array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?", "$t.ptable=?");
		$arrValues = array($intPid, $ptable);

		if (!static::isPreviewMode($arrOptions))
		{
			$arrColumns[] = "$t.invisible=''";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $arrValues, $arrOptions);
	}
}
