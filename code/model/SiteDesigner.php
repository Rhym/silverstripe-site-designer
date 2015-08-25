<?php

/**
 * Class SiteDesigner
 *
 * @property Varchar GoogleFontAPI
 */
class SiteDesigner extends DataObject implements TemplateGlobalProvider
{

    /**
     * @var array
     */
    private static $db = array(
        'GoogleFontAPI' => 'Varchar(255)'
    );

    /**
     * Default permission to check for 'LoggedInUsers' to create or edit pages
     *
     * @var array
     * @config
     */
    private static $required_permission = array('CMS_ACCESS_CMSMain', 'CMS_ACCESS_LeftAndMain');

    /**
     * Get the fields that are sent to the CMS. In
     * your extensions: updateCMSFields($fields)
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        /** @var FieldList $fields */
        $fields = FieldList::create(
            TabSet::create('Root',
                Tab::create('Main'),
                Tab::create('Settings')
            )
        );
        $fields->addFieldToTab('Root.Settings', TextField::create('GoogleFontAPI'));
        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    /**
     * Get the actions that are sent to the CMS. In
     * your extensions: updateEditFormActions($actions)
     *
     * @return Fieldset
     */
    public function getCMSActions()
    {
        if (Permission::check('ADMIN') || Permission::check('EDIT_SITECONFIG')) {
            /** @var FieldList $actions */
            $actions = FieldList::create(
                FormAction::create('save_sitedesigner', _t('CMSMain.SAVE', 'Save'))
                    ->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
            );
        } else {
            $actions = new FieldList();
        }

        $this->extend('updateCMSActions', $actions);

        return $actions;
    }

    /**
     * @return String
     */
    public function CMSEditLink()
    {
        return singleton('CMSSettingsController')->Link();
    }

    /**
     * @return DataObject|SiteConfig
     */
    static public function current_site_designer()
    {
        if ($siteDesigner = DataObject::get_one('SiteDesigner')) {
            return $siteDesigner;
        }
        return self::make_site_config();
    }

    /**
     * Setup a default SiteDesigner record if none exists
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        $siteDesigner = DataObject::get_one('SiteDesigner');
        if (!$siteDesigner) {
            self::make_site_designer();
            DB::alteration_message("Added default site designer", "created");
        }
    }

    /**
     * Create Designer with defaults from language file.
     *
     * @return SiteConfig
     */
    static public function make_site_designer()
    {
        /** @var SiteDesigner $config */
        $config = SiteDesigner::create();
        $config->write();
        return $config;
    }

    /**
     * @param null $member
     * @return bool
     */
    public function canView($member = null)
    {
        return true;
    }

    /**
     * @param null $member
     * @return bool
     */
    public function canEdit($member = null)
    {
        return true;
    }

    /**
     * Add $SiteDesigner to all SSViewers
     */
    public static function get_template_global_variables()
    {
        return array(
            'SiteDesigner' => 'current_site_designer',
        );
    }

}
