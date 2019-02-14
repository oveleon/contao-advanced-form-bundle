<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Reads and writes advanced forms
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property integer $jumpTo
 * @property boolean $sendViaEmail
 * @property string  $recipient
 * @property string  $subject
 * @property string  $format
 * @property boolean $skipEmpty
 * @property boolean $storeValues
 * @property string  $targetTable
 * @property string  $method
 * @property boolean $novalidate
 * @property string  $attributes
 * @property string  $formID
 * @property boolean $allowTags
 *
 * @method static AdvancedFormModel|null findById($id, array $opt=array())
 * @method static AdvancedFormModel|null findByPk($id, array $opt=array())
 * @method static AdvancedFormModel|null findByIdOrAlias($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneBy($col, $val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByTstamp($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByTitle($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByAlias($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByJumpTo($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneBySendViaEmail($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByRecipient($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneBySubject($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByFormat($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneBySkipEmpty($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByStoreValues($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByTargetTable($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByMethod($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByNovalidate($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByAttributes($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByFormID($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByTableless($val, array $opt=array())
 * @method static AdvancedFormModel|null findOneByAllowTags($val, array $opt=array())
 *
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByTitle($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByAlias($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByJumpTo($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findBySendViaEmail($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByRecipient($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findBySubject($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByFormat($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findBySkipEmpty($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByStoreValues($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByTargetTable($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByMethod($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByNovalidate($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByAttributes($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByFormID($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByTableless($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findByAllowTags($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findMultipleByIds($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormModel[]|AdvancedFormModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAlias($val, array $opt=array())
 * @method static integer countByJumpTo($val, array $opt=array())
 * @method static integer countBySendViaEmail($val, array $opt=array())
 * @method static integer countByRecipient($val, array $opt=array())
 * @method static integer countBySubject($val, array $opt=array())
 * @method static integer countByFormat($val, array $opt=array())
 * @method static integer countBySkipEmpty($val, array $opt=array())
 * @method static integer countByStoreValues($val, array $opt=array())
 * @method static integer countByTargetTable($val, array $opt=array())
 * @method static integer countByMethod($val, array $opt=array())
 * @method static integer countByNovalidate($val, array $opt=array())
 * @method static integer countByAttributes($val, array $opt=array())
 * @method static integer countByFormID($val, array $opt=array())
 * @method static integer countByTableless($val, array $opt=array())
 * @method static integer countByAllowTags($val, array $opt=array())
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class AdvancedFormModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_advanced_form';

	/**
	 * Get the maximum file size that is allowed for file uploads
	 *
	 * @return integer The maximum file size in bytes
	 */
	public function getMaxUploadFileSize()
	{
		$objResult = \Database::getInstance()->prepare("SELECT MAX(maxlength) AS maxlength FROM tl_form_field WHERE pid=? AND invisible='' AND type='upload' AND maxlength>0")
											 ->execute($this->id);

		if ($objResult->numRows > 0 && $objResult->maxlength > 0)
		{
			return $objResult->maxlength;
		}
		else
		{
			return \Config::get('maxFileSize');
		}
	}
}
