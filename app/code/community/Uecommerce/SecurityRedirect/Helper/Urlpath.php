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
class Uecommerce_SecurityRedirect_Helper_Urlpath extends Mage_Core_Helper_Abstract
{
    /**
     * Check if current URL is part of the URLs that need to be redirected
     * @param string $group
     * @return bool
     */
    public function urlPathIsValid($group)
    {
        $currentUrl = $this->hydrateUrl(Mage::app()->getFrontController()->getRequest()->getRequestUri());

        if (in_array($currentUrl, $this->getUrlPaths($group))) {
            return true;
        }

        return false;
    }

    /**
     * Get all URLs that need to be redirected if accessed
     * @param string $group
     * @return array
     */
    public function getUrlPaths($group)
    {
        $urlPaths = unserialize(Mage::getStoreConfig(
            Uecommerce_SecurityRedirect_Helper_Data::XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG .
            '/' . $group . '/url_paths')
        );

        return $urlPaths['routes'];
    }

    /**
     * @param string $url
     * @return string
     */
    public function hydrateUrl($url)
    {
        if (strlen($url) > 1) {
            if ($url{0} != '/') {
                $url = '/' . $url;
            }
            if (substr($url, -1, 1) != '/') {
                $url = $url . '/';
            }
        }

        return $url;
    }
}
