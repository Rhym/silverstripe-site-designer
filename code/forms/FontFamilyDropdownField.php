<?php

/**
 * Class FontFamilyDropdownField
 */
class FontFamilyDropdownField extends DropdownField
{

    public function __construct($name, $title = null, $source = array(), $value = '', $form = null, $emptyString = null)
    {
        /**
         * enable the chzn javascript
         */
        $this->addExtraClass('dropdown');
        parent::__construct($name, ($title === null) ? $name : $title, $value, $form);
    }

    /**
     * @param array $source
     * @return $this
     */
    public function setSource($source)
    {
        $fonts = $this->getGoogleFonts('all');
        if ($fonts) {
            $this->source = $fonts;
        } else {
            $this->source = array(
                _t('FontFamilyDropdownField.EnterAPI', '-- Please enter your API key --')
            );
        }
        return $this;
    }

    /**
     * @param int $amount
     * @return array
     */
    protected function getGoogleFonts($amount = 30)
    {
        if ($apiKey = SiteDesigner::current_site_designer()->GoogleFontAPI) {
            $fontFile = Director::baseFolder() . '/' . SITEDESIGNER_MODULE . '/fonts/google-web-fonts.txt';
            /**
             * Total time the file will be cached in seconds, set to a week
             */
            $cacheTime = 86400 * 7;
            if (file_exists($fontFile) && $cacheTime < filemtime($fontFile)) {
                $content = json_decode(file_get_contents($fontFile));
            } else {
                $url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $apiKey;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_REFERER, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $fontContent = curl_exec($ch);
                curl_close($ch);
                $fp = fopen($fontFile, 'w');
                fwrite($fp, $fontContent);
                fclose($fp);
                $content = json_decode($fontContent);
            }
            if (!empty($content->items)) {
                if ($amount == 'all') {
                    $googleFontsArray = $content->items;
                } else {
                    $googleFontsArray = array_slice($content->items, 0, $amount);
                }
            } else {
                return false;
            }
            $googleFontsDropdownArray = [];
            foreach ($googleFontsArray as $item) {
                $variants = ':' . implode(',', $item->variants);
                $googleFontsDropdownArray[$item->family . $variants] = $item->family;
            }
            return $googleFontsDropdownArray;
        }
        return false;
    }

}