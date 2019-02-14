<?php

namespace Oveleon\ContaoAdvancedFormBundle\Controller;

use Oveleon\ContaoAdvancedFormBundle\AdvancedForm;
use Oveleon\ContaoAdvancedFormBundle\AdvancedFormDataModel;
use Oveleon\ContaoAdvancedFormBundle\AdvancedFormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Handles the advanced form frontend routes.
 *
 * @Route(defaults={"_scope" = "frontend", "_token_check" = true})
 */
class AjaxController extends Controller
{
    /**
     * Runs the command scheduler.
     *
     * @return Response
     *
     * @Route("/contaoadvancedform", name="advanced_form")
     */
    public function indexAction()
    {
        $this->container->get('contao.framework')->initialize();

        return new Response('<html><body>not used</body></html>');
    }

    /**
     * Runs the command scheduler.
     *
     * @return Response
     *
     * @Route("/contaoadvancedform/{id}", name="advanced_form_action", requirements={"id"="\d+"})
     */
    public function formAction($id)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new \FrontendIndex();

        $objContent = \ContentModel::findByPk($id);

        if ($objContent === null)
        {
            return new Response('');
        }

        \Environment::set('hideScript', true);

        return new Response($controller->replaceInsertTags($controller->getContentElement($objContent)));
    }

    /**
     * Runs the command scheduler.
     *
     * @return Response
     *
     * @Route("/contaoadvancedform/{ceId}/edit/{moduleId}/{column}/{dataId}/start", name="advanced_form_start_edit_action", requirements={"ceId"="\d+"})
     */
    public function startEditAction($ceId, $moduleId, $column, $dataId)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new \FrontendIndex();

        $objData = AdvancedFormDataModel::findByPk($dataId);
        $rawData = \StringUtil::deserialize($objData->rawData);

        \Environment::set('hideScript', true);
        \Environment::set('AdvancedForm', $ceId);
        \Environment::set('AdvancedFormModule', $moduleId);
        \Environment::set('AdvancedFormColumn', $column);
        \Environment::set('AdvancedFormData', $rawData);
        $_SESSION['AdvancedFormDataID'] = $dataId;
        \Environment::set('AdvancedFormMode', 'update');
        \Environment::set('AdvancedFormUpdate', 'start');

        $objContentElement = \ContentModel::findByPk($ceId);
        $objArticle = \ArticleModel::findByPk($objContentElement->pid);
        $objPage = $objArticle->getRelated('pid');

        return $controller->renderPage($objPage);
    }

    /**
     * Runs the command scheduler.
     *
     * @return Response
     *
     * @Route("/contaoadvancedform/{ceId}/edit/{moduleId}/{column}/{dataId}", name="advanced_form_edit_action", requirements={"ceId"="\d+"})
     */
    public function editAction($ceId, $moduleId, $column, $dataId)
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new \FrontendIndex();

        $objModule = \ModuleModel::findByPk($moduleId);

        \Environment::set('hideScript', true);
        \Environment::set('AdvancedForm', $ceId);
        \Environment::set('AdvancedFormModule', $moduleId);
        \Environment::set('AdvancedFormColumn', $column);
        \Environment::set('AdvancedFormMode', 'update');

        $objContentElement = \ContentModel::findByPk($ceId);
        $objArticle = \ArticleModel::findByPk($objContentElement->pid);
        $objPage = \PageModel::findByPk($objArticle->pid);

        return $controller->renderPage($objPage);
    }
}