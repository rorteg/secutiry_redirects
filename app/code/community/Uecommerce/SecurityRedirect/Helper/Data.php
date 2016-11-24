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
class Uecommerce_SecurityRedirect_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG = 'dev';

    protected $_filesToRemove = [
        '/skin/adminhtml/default/default/media/flex.swf',
        '/skin/adminhtml/default/default/media/uploader.swf',
        '/skin/adminhtml/default/default/media/uploaderSingle.swf'
    ];

    protected $_filesSearchContents = [
        '/js/mage/adminhtml/uploader/instance.js' => 'fustyFlowFactory',
        '/skin/adminhtml/default/default/boxes.css' => 'background:url(images/blank.gif) repeat;'
    ];

    /**
     * Get extension version
     */
    public function getExtensionVersion()
    {
        return (string) Mage::getConfig()->getNode()->modules->Uecommerce_SecurityRedirect->version;
    }

    /**
     * Make sure that IP is in the white list of Magento and return false when the white list is empty.
     * @param int $storeId
     * @return bool
     */
    public function isIpAllowed($storeId = null)
    {
        $allow = true;
        $allowedIps = Mage::getStoreConfig(Mage_Core_Helper_Data::XML_PATH_DEV_ALLOW_IPS, $storeId);

        if (!Mage::helper('core')->isDevAllowed() || empty($allowedIps)) {
            $allow = false;
        }

        return $allow;
    }

    /**
     * Check the admin url path is "admin"
     * @return bool
     */
    public function checkAdminPathIsAdmin()
    {
        $isAdminCustomPath = Mage::getStoreConfig('admin/url/use_custom_path');
        $adminCustomPath = Mage::getStoreConfig('admin/url/custom_path');
        $return = false;

        if (!$isAdminCustomPath || $adminCustomPath == 'admin') {
            $return = true;
        }

        if (Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName')[0] != 'admin') {
            $return = false;
        }

        return $return;
    }

    /**
     * Check for vulnerable files according to the article:
     * https://support.hypernode.com/knowledgebase/magento-patch-supee-8788-release-1-9-3/
     * @return bool
     */
    public function checkVulnerableFilesExists()
    {
        // Check if vulnerable files on the list ($this->_filesToRemove) exist.
        $filesToRemove = array_filter($this->_filesToRemove, function ($file) {
            return file_exists(BP.$file);
        });

        // Check if the files in the list ($this->_filesSearchContents) contain the array values.
        $filesContents = array();
        foreach ($this->_filesSearchContents as $file => $content) {
            if (strpos(file_get_contents(BP . $file), $content) === false) {
                $filesContents[] = $file;
            }
        }

        return (count($filesToRemove) > 0) || (count($filesContents) > 0);
    }
}
