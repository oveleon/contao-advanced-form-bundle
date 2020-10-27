<?php

/*
 * This file is part of Oveleon AdvancedForm.
 *
 * (c) https://www.oveleon.de/
 */

namespace Oveleon\ContaoAdvancedFormBundle;

use Contao\FrontendUser;
use Model\Collection;
use Patchwork\Utf8;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Provide methods to handle advanced forms.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $formID
 * @property string  $method
 * @property boolean $allowTags
 * @property string  $attributes
 * @property boolean $novalidate
 * @property integer $jumpTo
 * @property boolean $sendViaEmail
 * @property boolean $skipEmpty
 * @property string  $format
 * @property string  $recipient
 * @property string  $subject
 * @property boolean $storeValues
 * @property string  $targetTable
 * @property string  $customTpl
 *
 * @author Fabian Ekert <fabian@oveleon.de>
 */
class AdvancedForm extends \Hybrid
{

	/**
	 * Model
	 * @var AdvancedFormModel
	 */
	protected $objModel;

	/**
	 * Key
	 * @var string
	 */
	protected $strKey = 'advancedForm';

	/**
	 * Table
	 * @var string
	 */
	protected $strTable = 'tl_advanced_form';

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'advanced_form_wrapper';

    /**
     * Form-ID
     * @var string
     */
    protected $formId;

    /**
     * Model
     * @var AdvancedFormPageModel
     */
    protected $objFormPage;

    /**
     * ModelCollection
     * @var \Model\Collection|AdvancedFormPageModel[]
     */
    protected $objFormPages;

    /**
     * Show prev button
     * @var boolean
     */
    protected $addPrevButton = true;

    /**
     * Show next button
     * @var boolean
     */
    protected $addNextButton = true;

    /**
     * Initialize the object
     *
     * @param AdvancedFormModel $objElement
     */
    public function __construct($objElement)
    {
        if ($objElement instanceof AdvancedFormModel)
        {
            $this->strKey = 'id';
        }

        // Load language file for field errors
        \System::loadLanguageFile('default');

        parent::__construct($objElement);
    }

    /**
     * Remove name attributes in the back end so the form is not validated
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['CTE']['advanced_form'][0]) . ' ###';
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->title;
            $objTemplate->href = 'contao/main.php?do=advanced_form&amp;table=tl_advanced_form_page&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $this->formId = $this->formID ? 'auto_'.$this->formID : 'auto_form_'.$this->id;

        if (($rawData = \Environment::get('AdvancedFormData')) !== null)
        {
            $_SESSION[$this->formId] = $rawData;
            unset($_SESSION['FORM_DATA']);
            \Input::setPost('FORM_SUBMIT', '');
        }

        $this->objFormPages = $this->getNecessaryFormPages();

        if ($this->objFormPages === null)
        {
            return;
        }

        $this->objFormPage = $this->getCurrentFormPage();

        $this->hideControlButton();

        return parent::generate();
    }

	/**
	 * Generate the advanced form
	 *
	 * @return string
	 */
	protected function compile()
	{
		$hasUpload = false;
		$doNotSubmit = false;
		$arrSubmitted = array();

		$this->loadDataContainer('tl_form_field');

		$this->Template->fields = '';
		$this->Template->hidden = '';
		$this->Template->formSubmit = $this->formId;
		$this->Template->formPage = $this->objFormPage->alias;
		$this->Template->method = ($this->method == 'GET') ? 'get' : 'post';
        $this->Template->addPrevButton = $this->addPrevButton;
        $this->Template->addNextButton = $this->addNextButton;
        $this->Template->labelPrev = 'Zurück';
        $this->Template->labelNext = $this->objFormPage->buttonLabel ? $this->objFormPage->buttonLabel : 'Weiter';
        $this->Template->ceId = $this->objParent->id;
        $this->Template->addScript = !\Environment::get('hideScript');

        if (\Environment::get('AdvancedFormMode') == 'update' && $this->objFormPage->editButtonLabel)
        {
            $this->Template->labelNext = $this->objFormPage->editButtonLabel;
        }

        $arrPageAttributes = \StringUtil::deserialize($this->objFormPage->cssID, true);

        if ($arrPageAttributes[0] != '')
        {
            $this->Template->pageCssID = ' id="' . $arrPageAttributes[0] . '"';
        }

        if ($arrPageAttributes[1] != '')
        {
            $this->Template->pageCss = $arrPageAttributes[1];
        }

        $progressMax = $this->objFormPages->count();
        $progressPointer = $this->getProgessPointer();
        $this->Template->progressMax = $progressMax;
        $this->Template->progressPointer = $progressPointer;
        $this->Template->progressPercent = $this->objFormPage->pageProgress !== null ? $this->objFormPage->pageProgress : 100 / $progressMax * $progressPointer;

		$this->initializeSession($this->formId);
		$arrLabels = array();

		// Get all form page fields
        $arrFields = array();
        $objFields = FormFieldModel::findPublishedByPid($this->objFormPage->id, AdvancedFormPageModel::getTable());

        if ($objFields !== null)
		{
			while ($objFields->next())
			{
				// Ignore the name of form fields which do not use a name (see #1268)
				if ($objFields->name != '' && isset($GLOBALS['TL_DCA']['tl_form_field']['palettes'][$objFields->type]) && preg_match('/[,;]name[,;]/', $GLOBALS['TL_DCA']['tl_form_field']['palettes'][$objFields->type]))
				{
					$arrFields[$objFields->name] = $objFields->current();
				}
				else
				{
					$arrFields[] = $objFields->current();
				}
			}
		}

		// HOOK: compile form fields
		if (isset($GLOBALS['TL_HOOKS']['compileFormFields']) && \is_array($GLOBALS['TL_HOOKS']['compileFormFields']))
		{
			foreach ($GLOBALS['TL_HOOKS']['compileFormFields'] as $callback)
			{
				$this->import($callback[0]);
				$arrFields = $this->{$callback[0]}->{$callback[1]}($arrFields, $this->formId, $this);
			}
		}

		// Process the fields
		if (!empty($arrFields) && \is_array($arrFields))
		{
			$row = 0;
			$max_row = \count($arrFields);

			foreach ($arrFields as $objField)
			{
				/** @var \FormFieldModel $objField */
				$strClass = $GLOBALS['TL_FFL'][$objField->type];

				// Continue if the class is not defined
				if (!class_exists($strClass))
				{
					continue;
				}

				$arrData = $objField->row();

				$arrData['decodeEntities'] = true;
				$arrData['allowHtml'] = $this->allowTags;
				$arrData['rowClass'] = 'row_'.$row . (($row == 0) ? ' row_first' : (($row == ($max_row - 1)) ? ' row_last' : '')) . ((($row % 2) == 0) ? ' even' : ' odd');

				// Increase the row count if its a password field
				if ($objField->type == 'password')
				{
					++$row;
					++$max_row;

					$arrData['rowClassConfirm'] = 'row_'.$row . (($row == ($max_row - 1)) ? ' row_last' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
				}

				// Submit buttons do not use the name attribute
				if ($objField->type == 'submit')
				{
					$arrData['name'] = '';
				}

				// Unset the default value depending on the field type (see #4722)
				if (!empty($arrData['value']))
				{
					if (!\in_array('value', \StringUtil::trimsplit('[,;]', $GLOBALS['TL_DCA']['tl_form_field']['palettes'][$objField->type])))
					{
						$arrData['value'] = '';
					}
				}

				/** @var \Widget $objWidget */
				$objWidget = new $strClass($arrData);
				$objWidget->required = $objField->mandatory ? true : false;

				// HOOK: load form field callback
				if (isset($GLOBALS['TL_HOOKS']['loadFormField']) && \is_array($GLOBALS['TL_HOOKS']['loadFormField']))
				{
					foreach ($GLOBALS['TL_HOOKS']['loadFormField'] as $callback)
					{
						$this->import($callback[0]);
						$objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
					}
				}

				// Validate the input
				if (\Input::post('FORM_SUBMIT') == $this->formId && \Input::post('FORM_ACTION') == 'next')
				{
					$objWidget->validate();

					// HOOK: validate form field callback
					if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
					{
						foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
						{
							$this->import($callback[0]);
							$objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
						}
					}

					if ($objWidget->hasErrors())
					{
						$doNotSubmit = true;
					}

					// Store current value in the session
					elseif ($objWidget->submitInput())
					{
						$arrSubmitted[$objField->name] = $objWidget->value;
						$_SESSION[$this->formId][$objField->name] = $objWidget->value;
						unset($_POST[$objField->name]); // see #5474
					}
				}

                // Validate the input
                if (\Input::post('FORM_SUBMIT') == preg_replace('/^auto_/i', '', $this->formId) && \Input::post('FORM_ACTION') == 'next')
                {
                    if ($_SESSION[$this->formId][$objField->name] != '')
                    {
                        $_POST[$objField->name] = $_SESSION[$this->formId][$objField->name];

                        $objWidget->validate();

                        // HOOK: validate form field callback
                        if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
                        {
                            foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
                            {
                                $this->import($callback[0]);
                                $objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
                            }
                        }
                    }
                }

                // Validate the input
                if (\Input::post('FORM_SUBMIT') == $this->formId && \Input::post('FORM_ACTION') == 'prev')
                {
                    $_POST[$objField->name] = $_SESSION[$this->formId][$objField->name];

                    $objWidget->validate();

                    // HOOK: validate form field callback
                    if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
                    {
                        foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
                        {
                            $this->import($callback[0]);
                            $objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
                        }
                    }

                    $doNotSubmit = true;
                }

                // Validate the input
                if (\Input::post('FORM_SUBMIT') == preg_replace('/^auto_/i', '', $this->formId) && \Input::post('FORM_ACTION') == 'prev')
                {
                    $_POST[$objField->name] = $_SESSION[$this->formId][$objField->name];

                    $objWidget->validate();

                    // HOOK: validate form field callback
                    if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
                    {
                        foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
                        {
                            $this->import($callback[0]);
                            $objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
                        }
                    }

                    $doNotSubmit = true;
                }

                if (\Input::post('FORM_ACTION') == '')
                {
                    if ($_SESSION[$this->formId][$objField->name] != '')
                    {
                        $_POST[$objField->name] = $_SESSION[$this->formId][$objField->name];

                        $objWidget->validate();

                        // HOOK: validate form field callback
                        if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
                        {
                            foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
                            {
                                $this->import($callback[0]);
                                $objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
                            }
                        }

                        $doNotSubmit = true;
                    }
                }

                if (\Environment::get('AdvancedFormMode') == 'update' && \Input::post('FORM_ACTION') == '')
                {
                    $_POST[$objField->name] = $_SESSION[$this->formId][$objField->name];

                    $objWidget->validate();

                    // HOOK: validate form field callback
                    if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && \is_array($GLOBALS['TL_HOOKS']['validateFormField']))
                    {
                        foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
                        {
                            $this->import($callback[0]);
                            $objWidget = $this->{$callback[0]}->{$callback[1]}($objWidget, $this->formId, $this->arrData, $this);
                        }
                    }
                }

				if ($objWidget instanceof \uploadable)
				{
					$hasUpload = true;
				}

				if ($objWidget instanceof \FormHidden)
				{
					$this->Template->hidden .= $objWidget->parse();
					--$max_row;
					continue;
				}

				if ($objWidget->name != '' && $objWidget->label != '')
				{
					$arrLabels[$objWidget->name] = $this->replaceInsertTags($objWidget->label); // see #4268
				}

				$this->Template->fields .= $objWidget->parse();
				++$row;
			}
		}

		// Process the form data
		if (\Input::post('FORM_SUBMIT') == $this->formId && !$doNotSubmit)
		{
			$this->processFormData($arrSubmitted, $arrLabels, $arrFields);
		}

		$strAttributes = '';
		$arrAttributes = \StringUtil::deserialize($this->attributes, true);

		if ($arrAttributes[0] != '')
		{
			$strAttributes .= ' id="' . $arrAttributes[0] . '"';
            $this->Template->formId = $arrAttributes[0];
		}

		if ($arrAttributes[1] != '')
		{
			$strAttributes .= ' class="' . $arrAttributes[1] . '"';
		}

		$this->Template->hasError = $doNotSubmit;
		$this->Template->attributes = $strAttributes;
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->action = str_replace('/start', '', \Environment::get('uri'));
		$this->Template->maxFileSize = $hasUpload ? $this->objModel->getMaxUploadFileSize() : false;
		$this->Template->novalidate = $this->novalidate ? ' novalidate' : '';

		// Get the target URL
		if ($this->method == 'GET' && $this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) instanceof PageModel)
		{
			/** @var \PageModel $objTarget */
			$this->Template->action = $objTarget->getFrontendUrl();
		}

		if ($this->objFormPage->clearData)
        {
            $_SESSION[$this->formId] = array('FORM_RESET'=>1);
        }

		return $this->Template->parse();
	}

	/**
	 * Process form data, store it in the session and redirect to the jumpTo page
	 *
	 * @param array $arrSubmitted
	 * @param array $arrLabels
	 * @param array $arrFields
	 */
	protected function processFormData($arrSubmitted, $arrLabels, $arrFields)
	{
        foreach ($_SESSION[$this->formId] as $k=>$v)
        {
            if ($k == 'SECOND_ACTION' || $k == 'FORM_PAGE' || $k == 'SECOND_ACTION_INDEX' || $k == 'FORM_PAGE_INDEX')
            {
                continue;
            }

            if (!array_key_exists($k, $arrSubmitted))
            {
                $arrSubmitted[$k] = $v;
            }
        }

		// HOOK: prepare form data callback
		if (isset($GLOBALS['TL_HOOKS']['prepareFormData']) && \is_array($GLOBALS['TL_HOOKS']['prepareFormData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['prepareFormData'] as $callback)
			{
				$this->import($callback[0]);
				$this->{$callback[0]}->{$callback[1]}($arrSubmitted, $arrLabels, $arrFields, $this);
			}
		}

        // Create a new member
        if ($this->objFormPage->createMember)
        {
            $arrData = array();

            // Default member field mapping
            $arrSessionFields = array('gender', 'firstname', 'lastname', 'email', 'username', 'password');
            foreach ($arrSessionFields as $field)
            {
                if (array_key_exists($field, $_SESSION[$this->formId]))
                {
                    $arrData[$field] = $_SESSION[$this->formId][$field];
                }
            }

            // Set email as username if no username is given
            if (!array_key_exists('username', $arrData) && array_key_exists('email', $arrData))
            {
                $arrData['username'] = $arrData['email'];
            }

            $arrData['tstamp'] = time();
            $arrData['login'] = $this->objFormPage->allowLogin;
            $arrData['activation'] = 'RG' . substr(md5(uniqid(mt_rand(), true)), 2);
            $arrData['dateAdded'] = $arrData['tstamp'];
            $arrData['groups'] = $this->objFormPage->groups;

            // Disable account
            $arrData['disable'] = 1;

            // Send activation e-mail
            if ($this->objFormPage->sendActivationMail)
            {
                $this->sendActivationMail($arrData);
            }

            // Create the user
            $objNewUser = new \MemberModel();
            $objNewUser->setRow($arrData);
            $objNewUser->save();

            // Assign home directory
            if ($this->objFormPage->assignDir)
            {
                $objHomeDir = \FilesModel::findByUuid($this->objFormPage->homeDir);

                if ($objHomeDir !== null)
                {
                    $this->import('Files');
                    $strUserDir = \StringUtil::standardize($arrData['username']) ?: 'user_' . $objNewUser->id;

                    // Add the user ID if the directory exists
                    while (is_dir(TL_ROOT . '/' . $objHomeDir->path . '/' . $strUserDir))
                    {
                        $strUserDir .= '_' . $objNewUser->id;
                    }

                    // Create the user folder
                    new \Folder($objHomeDir->path . '/' . $strUserDir);

                    $objUserDir = \FilesModel::findByPath($objHomeDir->path . '/' . $strUserDir);

                    // Save the folder ID
                    $objNewUser->assignDir = 1;
                    $objNewUser->homeDir = $objUserDir->uuid;
                    $objNewUser->save();
                }
            }

            // HOOK: send insert ID and user data
            if (isset($GLOBALS['TL_HOOKS']['createNewUser']) && \is_array($GLOBALS['TL_HOOKS']['createNewUser']))
            {
                foreach ($GLOBALS['TL_HOOKS']['createNewUser'] as $callback)
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($objNewUser->id, $arrData, $this);
                }
            }

            // Create the initial version (see #7816)
            $objVersions = new \Versions('tl_member', $objNewUser->id);
            $objVersions->setUsername($objNewUser->username);
            $objVersions->setUserId(0);
            $objVersions->setEditUrl('contao/main.php?do=member&act=edit&id=%s&rt=1');
            $objVersions->initialize();

            // Inform admin if no activation link is sent
            if (!$this->objFormPage->sendActivationMail)
            {
                $objEmail = new \Email();

                $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
                $objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
                $objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['adminSubject'], \Idna::decode(\Environment::get('host')));

                $strData = "\n\n";

                // Add user details
                foreach ($arrData as $k=>$v)
                {
                    if ($k == 'password' || $k == 'tstamp' || $k == 'activation' || $k == 'dateAdded')
                    {
                        continue;
                    }

                    $v = \StringUtil::deserialize($v);

                    if ($k == 'dateOfBirth' && \strlen($v))
                    {
                        $v = \Date::parse(\Config::get('dateFormat'), $v);
                    }

                    $strData .= $GLOBALS['TL_LANG']['tl_member'][$k][0] . ': ' . (\is_array($v) ? implode(', ', $v) : $v) . "\n";
                }

                $objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['adminText'], $objNewUser->id, $strData . "\n") . "\n";
                $objEmail->sendTo($GLOBALS['TL_ADMIN_EMAIL']);

                $this->log('A new user (ID ' . $objNewUser->id . ') has registered on the website', __METHOD__, TL_ACCESS);
            }
        }

        // Store the values in the database
        if ($this->storeValues && $this->objFormPage->storeValues)
        {
            $performUpdate = \Environment::get('AdvancedFormMode') == 'update';

            $arrSet = array();
            $arrSet['tstamp'] = time();
            $arrSet['rawData'] = serialize($_SESSION[$this->formId]);

            if (!$performUpdate)
            {
                unset($_SESSION[$this->formId]['id']);
                $arrSet['pid'] = $this->id;
                $arrSet['dateAdded'] = $arrSet['tstamp'];

                if (FE_USER_LOGGED_IN)
                {
                    $objUser = \FrontendUser::getInstance();
                    $arrSet['member'] = $objUser->id;
                    $arrSet['title'] = $objUser->username.' - [' . \Date::parse(\Config::get('datimFormat'), $arrSet['tstamp']) . ']';
                }
                else
                {
                    $arrSet['title'] = 'Gast - [' . \Date::parse(\Config::get('datimFormat'), $arrSet['tstamp']) . ']';
                }
            }

            $fieldMapping = \StringUtil::deserialize($this->fieldMapping, true);

            if (count($fieldMapping))
            {
                foreach ($fieldMapping as $mapping)
                {
                    if (isset($_SESSION[$this->formId][$mapping['value']]))
                    {
                        $arrSet[$mapping['label']] = $_SESSION[$this->formId][$mapping['value']];
                    }
                }
            }

            // Files
            if (!empty($_SESSION['FILES']))
            {
                foreach ($_SESSION['FILES'] as $k=>$v)
                {
                    if ($v['uploaded'])
                    {
                        $arrSet[$k] = \StringUtil::stripRootDir($v['tmp_name']);
                    }
                }
            }

            // Set the correct empty value (see #6284, #6373)
            foreach ($arrSet as $k=>$v)
            {
                if ($v === '')
                {
                    $arrSet[$k] = \Widget::getEmptyValueByFieldType($GLOBALS['TL_DCA']['tl_advanced_form_data']['fields'][$k]['sql']);
                }
            }

            if ($performUpdate)
            {
                $this->Database->prepare("UPDATE tl_advanced_form_data %s WHERE id=?")->set($arrSet)->execute($_SESSION['AdvancedFormDataID']);
            }
            else
            {
                $this->Database->prepare("INSERT INTO tl_advanced_form_data %s")->set($arrSet)->execute();
            }
        }

		// Send form data via e-mail
		if ($this->objFormPage->sendViaEmail)
		{
			$keys = array();
			$values = array();
			$fields = array();
			$message = '';

			foreach ($_SESSION[$this->formId] as $k=>$v)
			{
				if ($k == 'cc' || $k == 'SECOND_ACTION' || $k == 'FORM_PAGE')
				{
					continue;
				}

				$v = \StringUtil::deserialize($v);

				// Skip empty fields
				if ($this->objFormPage->skipEmpty && !\is_array($v) && !\strlen($v))
				{
					continue;
				}

				// Add field to message
				$message .= ($arrLabels[$k] ?: ucfirst($k)) . ': ' . (\is_array($v) ? implode(', ', $v) : $v) . "\n";

				// Prepare XML file
				if ($this->objFormPage->format == 'xml')
				{
					$fields[] = array
					(
						'name' => $k,
						'values' => (\is_array($v) ? $v : array($v))
					);
				}

				// Prepare CSV file
				if ($this->objFormPage->format == 'csv')
				{
					$keys[] = $k;
					$values[] = (\is_array($v) ? implode(',', $v) : $v);
				}
			}

			$recipients = \StringUtil::splitCsv($this->objFormPage->recipient);

			// Format recipients
			foreach ($recipients as $k=>$v)
			{
				$recipients[$k] = str_replace(array('[', ']', '"'), array('<', '>', ''), $v);
			}

			$email = new \Email();

			// Get subject and message
			if ($this->objFormPage->format == 'email')
			{
				$message = $arrSubmitted['message'];
				$email->subject = $arrSubmitted['subject'];
			}

			// Set the admin e-mail as "from" address
			$email->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$email->fromName = $GLOBALS['TL_ADMIN_NAME'];

			// Get the "reply to" address
			if (!empty(\Input::post('email', true)))
			{
				$replyTo = \Input::post('email', true);

				// Add the name
				if (!empty(\Input::post('name')))
				{
					$replyTo = '"' . \Input::post('name') . '" <' . $replyTo . '>';
				}
				elseif (!empty(\Input::post('firstname')) && !empty(\Input::post('lastname')))
				{
					$replyTo = '"' . \Input::post('firstname') . ' ' . \Input::post('lastname') . '" <' . $replyTo . '>';
				}

				$email->replyTo($replyTo);
			}

			// Fallback to default subject
			if (!$email->subject)
			{
				$email->subject = $this->replaceInsertTags($this->objFormPage->subject, false);
			}

			// Send copy to sender
			if (!empty($arrSubmitted['cc']))
			{
				$email->sendCc(\Input::post('email', true));
				unset($_SESSION[$this->formId]['cc']);
			}

			// Attach XML file
			if ($this->objFormPage->format == 'xml')
			{
				$objTemplate = new \FrontendTemplate('form_xml');
				$objTemplate->fields = $fields;
				$objTemplate->charset = \Config::get('characterSet');

				$email->attachFileFromString($objTemplate->parse(), 'form.xml', 'application/xml');
			}

			// Attach CSV file
			if ($this->objFormPage->format == 'csv')
			{
				$email->attachFileFromString(\StringUtil::decodeEntities('"' . implode('";"', $keys) . '"' . "\n" . '"' . implode('";"', $values) . '"'), 'form.csv', 'text/comma-separated-values');
			}

			$uploaded = '';

			// Attach uploaded files
			if (!empty($_SESSION['FILES']))
			{
				foreach ($_SESSION['FILES'] as $file)
				{
					// Add a link to the uploaded file
					if ($file['uploaded'])
					{
						$uploaded .= "\n" . \Environment::get('base') . \StringUtil::stripRootDir(\dirname($file['tmp_name'])) . '/' . rawurlencode($file['name']);
						continue;
					}

					$email->attachFileFromString(file_get_contents($file['tmp_name']), $file['name'], $file['type']);
				}
			}

			$uploaded = \strlen(trim($uploaded)) ? "\n\n---\n" . $uploaded : '';
			$email->text = \StringUtil::decodeEntities(trim($message)) . $uploaded . "\n\n";

			// Send the e-mail
			try
			{
				$email->sendTo($recipients);
			}
			catch (\Swift_SwiftException $e)
			{
				$this->log('Form "' . $this->objFormPage->title . '" could not be sent: ' . $e->getMessage(), __METHOD__, TL_ERROR);
			}

            //unset($_SESSION[$this->formId]);
		}

		// Store all values in the session
		foreach (array_keys($_POST) as $key)
		{
		    // ToDo: Wenn hier die Daten des Formulars nicht in FORM_DATA geschrieben werden, kann kein Seitenwechsel stattfinden. POST parameter FORM_PAGE fehlt in diesem Fall. Warum?
			$_SESSION['FORM_DATA'][$key] = $this->allowTags ? \Input::postHtml($key, true) : \Input::post($key, true);
		}

		$arrFiles = $_SESSION['FILES'];
		$arrData = array();

		if ($this->objFormPage !== null)
        {
            $arrData = $this->objFormPage->row();
        }

		// HOOK: process form data callback
		if (isset($GLOBALS['TL_HOOKS']['processFormData']) && \is_array($GLOBALS['TL_HOOKS']['processFormData']))
		{
			foreach ($GLOBALS['TL_HOOKS']['processFormData'] as $callback)
			{
				$this->import($callback[0]);
				$this->{$callback[0]}->{$callback[1]}($arrSubmitted, $arrData, $arrFiles, $arrLabels, $this);
			}
		}

		$_SESSION['FILES'] = array(); // DO NOT CHANGE

		// Add a log entry
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$this->log('Form "' . $this->title . '" has been submitted by "' . $this->User->username . '".', __METHOD__, TL_FORMS);
		}
		else
		{
			$this->log('Form "' . $this->title . '" has been submitted by a guest.', __METHOD__, TL_FORMS);
		}

		// Check whether there is a jumpTo page
		if (($objJumpTo = $this->objModel->getRelated('jumpTo')) instanceof PageModel)
		{
			$this->jumpToOrReload($objJumpTo->row());
		}

		$this->reload();
	}

    /**
     * Get all matching form page
     *
     * @return \Model\Collection|null
     */
    protected function getNecessaryFormPages()
    {
        $arrFormPages = array();
        $objFormPages = AdvancedFormPageModel::findPublishedByPid($this->id, $_SESSION['ADVANCED_FORM_GUEST_PAGE_VISIBLE'] ? false : true);

        if ($objFormPages === null)
        {
            return null;
        }

        if ($_SESSION['ADVANCED_FORM_GUEST_PAGE_VISIBLE'])
        {
            unset($_SESSION['ADVANCED_FORM_GUEST_PAGE_VISIBLE']);
        }

        while ($objFormPages->next())
        {
            $skip = false;

            $arrAndConditions = \StringUtil::deserialize($objFormPages->andConditions, true);
            $arrConditions = \StringUtil::deserialize($objFormPages->conditions, true);

            if (count($arrConditions) === 0 && count($arrAndConditions) === 0)
            {
                $arrFormPages[] = $objFormPages->current();
                continue;
            }

            if (!isset($_SESSION[$this->formId]))
            {
                continue;
            }

            foreach ($arrAndConditions as $andCondition)
            {
                if ($_SESSION[$this->formId][$andCondition['key']] === $andCondition['value'])
                {
                    continue;
                }
                else
                {
                    $skip = true;
                    break;
                }
            }

            if ($skip)
            {
                continue;
            }
            else
            {
                if (count($arrConditions) === 0)
                {
                    $arrFormPages[] = $objFormPages->current();
                    continue;
                }
            }

            foreach ($arrConditions as $condition)
            {
                if ($_SESSION[$this->formId][$condition['key']] === $condition['value'])
                {
                    $arrFormPages[] = $objFormPages->current();
                    break;
                }
            }
        }

        return new Collection($arrFormPages, AdvancedFormPageModel::getTable());
    }

    /**
     * Get current matching form page
     *
     * @return AdvancedFormPageModel
     */
    protected function getCurrentFormPage()
    {
        $objFormPage = null;

        if ($_SESSION[$this->formId]['FORM_RESET'])
        {
            unset($_SESSION[$this->formId]);
            unset($_SESSION['FORM_DATA']);

            return AdvancedFormPageModel::findOneByPid($this->id, array('order'=>'sorting ASC'));
        }

        if (\Environment::get('AdvancedFormUpdate') === 'start')
        {
            return AdvancedFormPageModel::findOneByPid($this->id, array('order'=>'sorting ASC'));
        }

        if (\Input::post('FORM_SUBMIT') == preg_replace('/^auto_/i', '', $this->formId) && !isset($_SESSION[$this->formId]['SECOND_ACTION']) && isset($_SESSION[$this->formId]['FORM_PAGE_INDEX']))
        {
            $_SESSION[$this->formId]['FORM_PAGE'] = $_SESSION[$this->formId]['FORM_PAGE_INDEX'];

            if ($_SESSION[$this->formId]['SECOND_ACTION_INDEX'] == 'next')
            {
                $objFormPage = $this->getNextFormPage();
            }
            elseif ($_SESSION[$this->formId]['SECOND_ACTION_INDEX'] == 'prev')
            {
                $objFormPage = $this->getPrevFormPage();
            }

            unset($_SESSION[$this->formId]['FORM_PAGE']);
        }
        elseif (\Input::post('FORM_SUBMIT') == $this->formId)
        {
            if (\Input::post('FORM_ACTION') == 'next')
            {
                $objFormPage = AdvancedFormPageModel::findOneByAlias(\Input::post('FORM_PAGE'));
            }
            elseif (\Input::post('FORM_ACTION') == 'prev')
            {
                $objFormPage = $this->getPrevFormPage();
            }

            $_SESSION[$this->formId]['SECOND_ACTION'] = \Input::post('FORM_ACTION');
            $_SESSION[$this->formId]['FORM_PAGE'] = \Input::post('FORM_PAGE');
            $_SESSION[$this->formId]['SECOND_ACTION_INDEX'] = \Input::post('FORM_ACTION');
            $_SESSION[$this->formId]['FORM_PAGE_INDEX'] = \Input::post('FORM_PAGE');
        }
        elseif (isset($_SESSION[$this->formId]['SECOND_ACTION']))
        {
            if ($_SESSION[$this->formId]['SECOND_ACTION'] == 'next')
            {
                $objFormPage = $this->getNextFormPage();
            }
            elseif ($_SESSION[$this->formId]['SECOND_ACTION'] == 'prev')
            {
                $objFormPage = $this->getPrevFormPage();
            }

            unset($_SESSION[$this->formId]['SECOND_ACTION']);
            unset($_SESSION[$this->formId]['FORM_PAGE']);
        }
        /*elseif (isset($_SESSION[$this->formId]['FORM_PAGE']))
        {
            $objFormPage = AdvancedFormPageModel::findByAlias($_SESSION[$this->formId]['FORM_PAGE']);
        }*/
        else
        {
            $objFormPage = AdvancedFormPageModel::findOneByPid($this->id, array('order'=>'sorting ASC'));
        }

        return $objFormPage;
    }

    /**
     * Determine the next matching form page
     *
     * @return AdvancedFormPageModel
     */
    protected function getNextFormPage()
    {
        $formPageAlias = $_SESSION[$this->formId]['FORM_PAGE'];
        $skip = true;

        while ($this->objFormPages->next())
        {
            if ($this->objFormPages->alias === $formPageAlias)
            {
                $skip = false;
                continue;
            }

            if ($skip)
            {
                continue;
            }

            return $this->objFormPages->current();
        }
    }

    /**
     * Determine the previous matching form page
     *
     * @return AdvancedFormPageModel
     */
    protected function getPrevFormPage()
    {
        $formPageAlias = $_SESSION[$this->formId]['FORM_PAGE'];
        $hit = false;

        while ($this->objFormPages->next())
        {
            if ($this->objFormPages->alias === $formPageAlias)
            {
                $hit = true;
            }

            if ($hit)
            {
                $this->objFormPages->prev();

                return $this->objFormPages->current();
            }
        }
    }

    /**
     * Determine the current progress pointer
     *
     * @return integer
     */
    protected function getProgessPointer()
    {
        $this->objFormPages->reset();

        $i = 1;

        while ($this->objFormPages->next())
        {
            if ($this->objFormPages->id === $this->objFormPage->id)
            {
                return $i;
            }

            $i++;
        }

        return $i;
    }

    /**
     * Determine the previous matching form page
     *
     * @return AdvancedFormPageModel
     */
    protected function hideControlButton()
    {
        $this->addPrevButton = !$this->objFormPage->hidePrevButton;

        $this->objFormPages->first();
        if ($this->objFormPages->id === $this->objFormPage->id)
        {
            $this->addPrevButton = false;
        }

        $this->objFormPages->last();
        if ($this->objFormPages->id === $this->objFormPage->id)
        {
            $this->addNextButton = false;
        }

        $this->objFormPages->reset();
    }

	/**
	 * Get the maximum file size that is allowed for file uploads
	 *
	 * @return integer
	 *
	 * @deprecated Deprecated since Contao 4.0, to be removed in Contao 5.0.
	 *             Use $this->objModel->getMaxUploadFileSize() instead.
	 */
	protected function getMaxFileSize()
	{
		@trigger_error('Using Form::getMaxFileSize() has been deprecated and will no longer work in Contao 5.0. Use $this->objModel->getMaxUploadFileSize() instead.', E_USER_DEPRECATED);

		return $this->objModel->getMaxUploadFileSize();
	}

	/**
	 * Initialize the form in the current session
	 *
	 * @param string $formId
	 */
	protected function initializeSession($formId)
	{
		if (\Input::post('FORM_SUBMIT') != $formId)
		{
			return;
		}

		$arrMessageBox = array('TL_ERROR', 'TL_CONFIRM', 'TL_INFO');
		$_SESSION[$this->formId] = \is_array($_SESSION[$this->formId]) ? $_SESSION[$this->formId] : array();

		foreach ($arrMessageBox as $tl)
		{
			if (\is_array($_SESSION[$formId][$tl]))
			{
				$_SESSION[$formId][$tl] = array_unique($_SESSION[$formId][$tl]);

				foreach ($_SESSION[$formId][$tl] as $message)
				{
					$objTemplate = new \FrontendTemplate('form_message');
					$objTemplate->message = $message;
					$objTemplate->class = strtolower($tl);

					$this->Template->fields .= $objTemplate->parse() . "\n";
				}

				$_SESSION[$formId][$tl] = array();
			}
		}
	}

    /**
     * Send the activation mail
     *
     * @param array $arrData
     */
    protected function sendActivationMail($arrData)
    {
        // Prepare the simple token data
        $arrTokenData = $arrData;
        $arrTokenData['domain'] = \Idna::decode(\Environment::get('host'));
        $arrTokenData['link'] = \Idna::decode(\Environment::get('base')) . \PageModel::findById($this->objFormPage->registrationPage)->getFrontendUrl() . '?token=' . $arrData['activation'];
        $arrTokenData['channels'] = '';

        $bundles = \System::getContainer()->getParameter('kernel.bundles');

        if (isset($bundles['ContaoNewsletterBundle']))
        {
            // Make sure newsletter is an array
            if (!\is_array($arrData['newsletter']))
            {
                if ($arrData['newsletter'] != '')
                {
                    $arrData['newsletter'] = array($arrData['newsletter']);
                }
                else
                {
                    $arrData['newsletter'] = array();
                }
            }

            // Replace the wildcard
            if (!empty($arrData['newsletter']))
            {
                $objChannels = \NewsletterChannelModel::findByIds($arrData['newsletter']);

                if ($objChannels !== null)
                {
                    $arrTokenData['channels'] = implode("\n", $objChannels->fetchEach('title'));
                }
            }
        }

        // Deprecated since Contao 4.0, to be removed in Contao 5.0
        $arrTokenData['channel'] = $arrTokenData['channels'];

        $objEmail = new \Email();

        $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
        $objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
        $objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['emailSubject'], \Idna::decode(\Environment::get('host')));
        $objEmail->text = \StringUtil::parseSimpleTokens($this->objFormPage->activationText, $arrTokenData);

        // Send the e-mail
        try
        {
            $objEmail->sendTo($arrData['email']);
        }
        catch (\Swift_SwiftException $e)
        {
            $this->log('Form "' . $this->objFormPage->title . '" could not be sent: ' . $e->getMessage(), __METHOD__, TL_ERROR);
        }
    }
}