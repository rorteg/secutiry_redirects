<?php

class Uecommerce_SecurityRedirect_Test_Unit_Helper_UrlpathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Uecommerce_SecurityRedirect_Helper_Urlpath
     */
    private $helper;
    private $group = 'uecommerce_securityredirect';

    protected function setUp()
    {
        $this->helper = new Uecommerce_SecurityRedirect_Helper_Urlpath();
        Mage::app()->getStore(null)->setConfig(
            Uecommerce_SecurityRedirect_Helper_Data::XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG .
            '/' . $this->group . '/url_paths', @serialize([
                'routes' => [
                    '/downloader/'
                ]
        ]));

        Mage::app()->getFrontController()->getRequest()->setRequestUri('/downloader/');
    }

    public function testUrlPathIsValidByConfigGroup()
    {
        $this->assertContains('/downloader/', $this->helper->getUrlPaths($this->group));

        $this->helper->setCurrentUrl('/downloader/');
        $this->assertTrue($this->helper->urlPathIsValidByConfigGroup($this->group));
    }

    public function testHydrateUrlResult()
    {
        $this->assertEquals('/downloader/', $this->helper->hydrateUrl('downloader'));
    }

}