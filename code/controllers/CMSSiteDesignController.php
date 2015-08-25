<?php

/**
 * Class CMSSiteDesignController
 */
class CMSSiteDesignController extends LeftAndMain
{

    /**
     * @var string
     */
    private static $url_segment = 'site-design';

    /**
     * @var string
     */
    private static $url_rule = '/$Action/$ID/$OtherID';

    /**
     * @var int
     */
    private static $menu_priority = 0;

    /**
     * @var string
     */
    private static $menu_title = 'Site Design';

    /**
     * @var string
     */
    private static $tree_class = 'SiteDesigner';

    /**
     * @var string
     */
    private static $menu_icon = 'silverstripe-site-designer/images/icons/admin/brush.png';

    /**
     * @var string
     */
    private static $menu_icon_class = 'fa fa-paint-brush';

    /**
     * @var array
     */
    private static $required_permission_codes = array('EDIT_SITECONFIG');

    public function init()
    {
        parent::init();
        Requirements::javascript(CMS_DIR . '/javascript/CMSMain.EditForm.js');
    }

    /**
     * @return PjaxResponseNegotiator
     */
    public function getResponseNegotiator()
    {
        $neg = parent::getResponseNegotiator();
        $controller = $this;
        $neg->setCallback('CurrentForm', function () use (&$controller) {
            return $controller->renderWith($controller->getTemplatesWithSuffix('_Content'));
        });
        return $neg;
    }

    /**
     * @param null $id Not used.
     * @param null $fields Not used.
     * @return Form
     */
    public function getEditForm($id = null, $fields = null)
    {
        /** =========================================
         * @var SiteDesigner $siteDesigner
         * @var CMSForm $form
        ===========================================*/

        $siteDesigner = SiteDesigner::current_site_designer();
        $fields = $siteDesigner->getCMSFields();

        // Tell the CMS what URL the preview should show
        $fields->push(new HiddenField('PreviewURL', 'Preview URL', RootURLController::get_homepage_link()));
        // Added in-line to the form, but plucked into different view by LeftAndMain.Preview.js upon load
        $fields->push($navField = new LiteralField('SilverStripeNavigator', $this->getSilverStripeNavigator()));
        $navField->setAllowHTML(true);

        $actions = $siteDesigner->getCMSActions();
        $form = CMSForm::create(
            $this, 'EditForm', $fields, $actions
        )->setHTMLID('Form_EditForm');
        $form->setResponseNegotiator($this->getResponseNegotiator());
        $form->addExtraClass('cms-content center cms-edit-form');
        // don't add data-pjax-fragment=CurrentForm, its added in the content template instead

        if ($form->Fields()->hasTabset()) {
            $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
        }
        $form->setHTMLID('Form_EditForm');
        $form->loadDataFrom($siteDesigner);
        $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));

        // Use <button> to allow full jQuery UI styling
        $actions = $actions->dataFields();
        if ($actions) {
            foreach ($actions as $action) {
                $action->setUseButtonTag(true);
            }
        }

        $this->extend('updateEditForm', $form);

        return $form;
    }

    /**
     * Used for preview controls, mainly links which switch between different states of the page.
     *
     * @return ArrayData
     */
    public function getSilverStripeNavigator()
    {
        return $this->renderWith('CMSSettingsController_SilverStripeNavigator');
    }

    /**
     * Save the current sites {@link SiteConfig} into the database
     *
     * @param array $data
     * @param Form $form
     * @return String
     */
    public function save_sitedesigner($data, $form)
    {
        $siteDesigner = SiteDesigner::current_site_designer();
        $form->saveInto($siteDesigner);

        try {
            $siteDesigner->write();
        } catch (ValidationException $ex) {
            $form->sessionMessage($ex->getResult()->message(), 'bad');
            return $this->getResponseNegotiator()->respond($this->request);
        }

        $this->response->addHeader('X-Status', rawurlencode(_t('LeftAndMain.SAVEDUP', 'Saved.')));
        return $this->getResponseNegotiator()->respond($this->request);
    }

    /**
     * @return string
     */
    public function LinkPreview()
    {
        $record = $this->getRecord($this->currentPageID());
        $baseLink = ($record && $record instanceof Page) ? $record->Link('?stage=Stage') : Director::absoluteBaseURL();
        return $baseLink;
    }

    /**
     * @param bool|false $unlinked
     * @return ArrayList
     */
    public function Breadcrumbs($unlinked = false)
    {
        $defaultTitle = self::menu_title_for_class(get_class($this));
        return new ArrayList(array(
            new ArrayData(array(
                'Title' => _t("{$this->class}.MENUTITLE", $defaultTitle),
                'Link' => false
            ))
        ));
    }

}
