<?php

class Uecommerce_SecurityRedirect_Test_Unit_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Uecommerce_SecurityRedirect_Helper_Data
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Uecommerce_SecurityRedirect_Helper_Data();
    }

    public function testGetExtensionVersion()
    {
        $fakeVersion = '1.0.0';
        Mage::getConfig()->setNode('modules/Uecommerce_SecurityRedirect/version', $fakeVersion);

        $this->assertEquals($this->_helper->getExtensionVersion(), $fakeVersion);
    }

    public function testIsIpAllowedWhenReturnExpectedIsFalse()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        Mage::app()->getStore(null)->setConfig(Mage_Core_Helper_Data::XML_PATH_DEV_ALLOW_IPS, '');

        $this->assertFalse($this->_helper->isIpAllowed(null));
    }

    public function testIsIpAllowedWhenReturnExpectedIsTrue()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        Mage::app()->getStore(null)->setConfig(Mage_Core_Helper_Data::XML_PATH_DEV_ALLOW_IPS, '127.0.0.1');

        $this->assertTrue($this->_helper->isIpAllowed(null));
    }

    public function testCheckAdminPathIsAdminWhenXmlConfigIsAdmin()
    {
        Mage::getConfig()->setNode('admin/routers/adminhtml/args/frontName', 'admin');
        $this->assertTrue($this->_helper->checkAdminPathIsAdmin());
    }


    public function testCheckAdminPathIsAdminWhenXmlConfigNotAdmin()
    {
        Mage::getConfig()->setNode('admin/routers/adminhtml/args/frontName', 'admin_custom');
        Mage::app()->getStore(null)->setConfig('admin/url/use_custom_path', 1);
        Mage::app()->getStore(null)->setConfig('admin/url/custom_path', 'custom_admin');

        $this->assertFalse($this->_helper->checkAdminPathIsAdmin(true));
    }

    public function testCheckAdminPathIsAdminWhenSystemConfigIsAdmin()
    {
        Mage::getConfig()->setNode('admin/routers/adminhtml/args/frontName', 'admin_custom');
        Mage::app()->getStore(null)->setConfig('admin/url/use_custom_path', 1);
        Mage::app()->getStore(null)->setConfig('admin/url/custom_path', 'admin');

        $this->assertTrue($this->_helper->checkAdminPathIsAdmin());
    }

    public function testCheckAdminPathIsAdminWhenSystemConfigNotAdmin()
    {
        Mage::getConfig()->setNode('admin/routers/adminhtml/args/frontName', 'admin_custom');
        Mage::app()->getStore(null)->setConfig('admin/url/use_custom_path', 1);
        Mage::app()->getStore(null)->setConfig('admin/url/custom_path', 'admin_custom');

        $this->assertFalse($this->_helper->checkAdminPathIsAdmin());
    }

}