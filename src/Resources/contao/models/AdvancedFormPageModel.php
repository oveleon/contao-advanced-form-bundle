<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

/**
 * Reads and writes advanced form pages
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $sorting
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property string  $conditions
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 *
 * @method static AdvancedFormPageModel|null findById($id, array $opt=array())
 * @method static AdvancedFormPageModel|null findByPk($id, array $opt=array())
 * @method static AdvancedFormPageModel|null findByIdOrAlias($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneBy($col, $val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByPid($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByTSorting($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByTstamp($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByTitle($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByAlias($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByConditions($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByPublished($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByStart($val, array $opt=array())
 * @method static AdvancedFormPageModel|null findOneByStop($val, array $opt=array())
 *
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByPid($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findBySorting($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByTitle($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByAlias($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByConditions($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByPublished($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByStart($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findByStop($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findMultipleByIds($val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByPid($id, array $opt=array())
 * @method static integer countBySorting($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAlias($val, array $opt=array())
 * @method static integer countByConditions($val, array $opt=array())
 * @method static integer countByPublished($val, array $opt=array())
 * @method static integer countByStart($val, array $opt=array())
 * @method static integer countByStop($val, array $opt=array())
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class AdvancedFormPageModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_advanced_form_page';

    /**
     * Find published advanced form pages by their parent ID and exclude pages only visible by guests
     *
     * @param integer $intPid     The advanced form page ID
     * @param array   $arrOptions An optional options array
     *
     * @return \Model\Collection|AdvancedFormPageModel[]|AdvancedFormPageModel|null A collection of models or null if there are no form fields
     */
    public static function findPublishedByPid($intPid, $allowUserStatus=true, array $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = array("$t.pid=?");
#
        if (FE_USER_LOGGED_IN && $allowUserStatus)
        {
            $arrColumns[] = "$t.guests=''";
        }

        if (\Environment::get('AdvancedFormMode') !== 'update')
        {
            $arrColumns[] = "$t.editMode=''";
        }

        if (!static::isPreviewMode($arrOptions))
        {
            $time = \Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.sorting";
        }

        return static::findBy($arrColumns, $intPid, $arrOptions);
    }
}
