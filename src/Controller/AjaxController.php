<?php

namespace Oveleon\ContaoAdvancedFormBundle\Controller;

use Contao\ArticleModel;
use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\FrontendIndex;
use Contao\ModuleModel;
use Contao\PageModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Contao\System;
use Exception;
use Oveleon\ContaoAdvancedFormBundle\AdvancedForm;
use Oveleon\ContaoAdvancedFormBundle\AdvancedFormDataModel;
use Oveleon\ContaoAdvancedFormBundle\AdvancedFormModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles the advanced form frontend routes.
 *
 * @Route(defaults={"_scope" = "frontend", "_token_check" = true})
 */
class AjaxController extends AbstractController
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Runs the command scheduler.
     *
     * @return Response
     *
     * @Route("/contaoadvancedform", name="advanced_form")
     */
    public function indexAction(): Response
    {
        $this->framework->initialize();

        return new Response('<html><body>not used</body></html>');
    }

    /**
     * Runs the command scheduler.
     *
     * @param $id
     * @return Response
     *
     * @Route("/contaoadvancedform/{id}", name="advanced_form_action", requirements={"id"="\d+"})
     */
    public function formAction($id): Response
    {
        $this->framework->initialize();

        $controller = new FrontendIndex();

        $objContent = ContentModel::findByPk($id);

        if ($objContent === null)
        {
            return new Response('');
        }

        Environment::set('hideScript', true);

        return new Response($controller->replaceInsertTags($controller->getContentElement($objContent)));
    }

    /**
     * Runs the command scheduler.
     *
     * @param $ceId
     * @param $moduleId
     * @param $column
     * @param $dataId
     * @return Response
     *
     * @throws Exception
     * @Route("/contaoadvancedform/{ceId}/edit/{moduleId}/{column}/{dataId}/start", name="advanced_form_start_edit_action", requirements={"ceId"="\d+"})
     */
    public function startEditAction($ceId, $moduleId, $column, $dataId): Response
    {
        $this->framework->initialize();

        $controller = new FrontendIndex();

        $objData = AdvancedFormDataModel::findByPk($dataId);
        $rawData = StringUtil::deserialize($objData->rawData);

        Environment::set('hideScript', true);
        Environment::set('AdvancedForm', $ceId);
        Environment::set('AdvancedFormModule', $moduleId);
        Environment::set('AdvancedFormColumn', $column);
        Environment::set('AdvancedFormData', $rawData);
        $_SESSION['AdvancedFormDataID'] = $dataId;
        Environment::set('AdvancedFormMode', 'update');
        Environment::set('AdvancedFormUpdate', 'start');

        $objContentElement = ContentModel::findByPk($ceId);
        $objArticle = ArticleModel::findByPk($objContentElement->pid);
        $objPage = $objArticle->getRelated('pid');

        return $controller->renderPage($objPage);
    }

    /**
     * Runs the command scheduler.
     *
     * @param $ceId
     * @param $moduleId
     * @param $column
     * @param $dataId
     * @return Response
     *
     * @Route("/contaoadvancedform/{ceId}/edit/{moduleId}/{column}/{dataId}", name="advanced_form_edit_action", requirements={"ceId"="\d+"})
     */
    public function editAction($ceId, $moduleId, $column, $dataId): Response
    {
        $this->framework->initialize();

        $controller = new FrontendIndex();

        $objModule = ModuleModel::findByPk($moduleId);

        Environment::set('hideScript', true);
        Environment::set('AdvancedForm', $ceId);
        Environment::set('AdvancedFormModule', $moduleId);
        Environment::set('AdvancedFormColumn', $column);
        Environment::set('AdvancedFormMode', 'update');

        $objContentElement = ContentModel::findByPk($ceId);
        $objArticle = ArticleModel::findByPk($objContentElement->pid);
        $objPage = PageModel::findByPk($objArticle->pid);

        return $controller->renderPage($objPage);
    }
}
