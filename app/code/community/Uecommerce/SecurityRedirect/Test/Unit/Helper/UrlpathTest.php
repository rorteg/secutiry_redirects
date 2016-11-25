<?php

class Uecommerce_SecurityRedirect_Test_Unit_Helper_UrlpathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Uecommerce_SecurityRedirect_Helper_Urlpath
     */
    private $helper;
    private $group = 'scureandredirect';

    protected function setUp()
    {
        $this->helper = new Uecommerce_SecurityRedirect_Helper_Urlpath();
        Mage::app()->getStore(null)->setConfig(
            Uecommerce_SecurityRedirect_Helper_Data::XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG .
            '/' . $this->group . '/url_paths', @serialize([
                '/downloader/'
        ]));
    }

    public function testUrlPathIsValidByConfigGroup()
    {
        print_r($this->helper->getUrlPaths($this->group));
    }
}