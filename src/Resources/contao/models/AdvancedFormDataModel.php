<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Reads and writes advanced form data
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $rawData
 * @property string  $title
 * @property integer $member
 * @property string  $textfield1
 * @property string  $textfield2
 * @property string  $textfield3
 * @property string  $textfield4
 * @property string  $textfield5
 * @property string  $textfield6
 * @property integer $numberfield1
 * @property integer $numberfield2
 * @property integer $numberfield3
 * @property integer $numberfield4
 * @property integer $numberfield5
 * @property integer $numberfield6
 * @property string  $textarea1
 * @property string  $textarea2
 * @property string  $textarea3
 *
 * @method static AdvancedFormDataModel|null findById($id, array $opt=array())
 * @method static AdvancedFormDataModel|null findByPk($id, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneBy($col, $val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByPid($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTstamp($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByRawData($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTitle($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByMember($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield1($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield2($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield3($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield4($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield5($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextfield6($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield1($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield2($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield3($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield4($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield5($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByNumberfield6($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextarea1($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextarea2($val, array $opt=array())
 * @method static AdvancedFormDataModel|null findOneByTextarea3($val, array $opt=array())
 *
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByPid($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByRawData($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTitle($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByMember($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield1($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield2($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield3($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield4($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield5($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextfield6($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield1($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield2($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield3($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield4($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield5($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByNumberfield6($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextarea1($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextarea2($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findByTextarea3($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findMultipleByIds($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormDataModel[]|AdvancedFormDataModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByPid($val, array $opt=array())
 * @method static integer countByRawData($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByMember($val, array $opt=array())
 * @method static integer countByTextfield1($val, array $opt=array())
 * @method static integer countByTextfield2($val, array $opt=array())
 * @method static integer countByTextfield3($val, array $opt=array())
 * @method static integer countByTextfield4($val, array $opt=array())
 * @method static integer countByTextfield5($val, array $opt=array())
 * @method static integer countByTextfield6($val, array $opt=array())
 * @method static integer countByNumberfield1($val, array $opt=array())
 * @method static integer countByNumberfield2($val, array $opt=array())
 * @method static integer countByNumberfield3($val, array $opt=array())
 * @method static integer countByNumberfield4($val, array $opt=array())
 * @method static integer countByNumberfield5($val, array $opt=array())
 * @method static integer countByNumberfield6($val, array $opt=array())
 * @method static integer countByTextarea1($val, array $opt=array())
 * @method static integer countByTextarea2($val, array $opt=array())
 * @method static integer countByTextarea3($val, array $opt=array())
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class AdvancedFormDataModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_advanced_form_data';

    /**
     * Find an advanced form data item by its ID and member
     *
     * @param integer $varId      The numeric ID
     * @param integer $memberId   The member ID
     * @param array   $arrOptions An optional options array
     *
     * @return AdvancedFormDataModel|null The model or null
     */
	public static function findByIdAndMember($varId, $memberId, array $arrOptions=array())
    {
        $t = static::$strTable;

        $arrColumns = array("$t.id=? AND member=?");
        $arrValues = array($varId, $memberId);

        return static::findOneBy($arrColumns, $arrValues, $arrOptions);
    }
}
