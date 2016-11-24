<?php
/**
 * Uecommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Uecommerce EULA.
 * It is also available through the world-wide-web at this URL:
 * http://www.uecommerce.com.br/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.uecommerce.com.br/ for more information
 *
 * @category   Uecommerce
 * @package    Uecommerce_SecurityRedirect
 * @copyright  Copyright (c) 2016 Uecommerce (http://www.uecommerce.com.br/)
 * @license    http://www.uecommerce.com.br/
 * @author     Uecommerce Dev Team
 */

class Uecommerce_SecurityRedirect_Model_Observer
{
    /**
     * @var Mage_Core_Controller_Varien_Front
     */
    private $frontController;

    /**
     * Redirect if needed
     * @param Varien_Event_Observer $observer
     * @return bool|void
     */
    public function checkAndRedirect(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfig('dev/uecommerce_securityredirect/active')) {
            return false;
        }

        $this->frontController = Mage::app()->getFrontController();
        $urlHelper = $this->getUrlPathHelper();

        if ($urlHelper->urlPathIsValidByConfigGroup('uecommerce_securityredirect')
            && !$this->getHelper()->isIpAllowed()) {
            return $this->setRedirectToHomePage();
        }
    }

    /**
     * Redirect to Home Page
     */
    private function setRedirectToHomePage()
    {
        try {
            $response = $this->frontController->getResponse();
            $response->setRedirect(Mage::getBaseUrl());
            $response->sendResponse();
            $response->setDispatched(true);

            return;
        } catch (Exception $e) {
            Mage::logException($e);
            return;
        }
    }

    /**
     * @return Mage_Core_Helper_Abstract|Uecommerce_SecurityRedirect_Helper_Urlpath
     */
    private function getUrlPathHelper()
    {
        return Mage::helper('uecommerce_securityredirect/urlpath');
    }

    /**
     * @return Mage_Core_Helper_Abstract|Uecommerce_SecurityRedirect_Helper_Data
     */
    private function getHelper()
    {
        return Mage::helper('uecommerce_securityredirect');
    }

    /**
     * Add critical message when the admin path is admin.
     */
    public function checkAdminPath()
    {
        if ($this->getHelper()->checkAdminPathIsAdmin()) {
            /** @var Mage_AdminNotification_Model_Inbox $notification */
            $notification = Mage::getModel('adminnotification/inbox');

            $notification->addCritical(
                Mage::helper('uecommerce_securityredirect')->__(
                    "Attention! In order to avoid Brute Force Attacks it is strictly recommended that you change your 
                    URL to access the administrative area (from 'admin' to another word). Click in 'Read Details' 
                    to go to the settings and Click in 'Admin Base URL'. "
                ),
                '',
                Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/admin')
            );
        }
    }

    /**
     * Check for vulnerable files according to the article:
     * https://support.hypernode.com/knowledgebase/magento-patch-supee-8788-release-1-9-3/
     */
    public function checkVulnerableFiles()
    {
        if ($this->getHelper()->checkVulnerableFilesExists()) {
            /** @var Mage_AdminNotification_Model_Inbox $notification */
            $notification = Mage::getModel('adminnotification/inbox');

            $notification->addCritical(
                Mage::helper('uecommerce_securityredirect')->__(
                    "Attention! Your installation has vulnerable files! 
                    Please install PATCH SUPEE-8788 immediately
                     for your Magento to fix this critical security issue!"
                ),
                Mage::helper('uecommerce_securityredirect')->__(
                    "Please install PATCH SUPEE-8788 immediately for your Magento 
                    to fix this critical security issue!"
                ),
                'https://magento.com/tech-resources/download'
            );
        }
    }
}
