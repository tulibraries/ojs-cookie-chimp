<?php

/**
 * Site-wide CookieChimp integration for OJS 3.4.x.
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

namespace APP\plugins\generic\cookieChimp;

use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use PKP\config\Config;

class CookieChimpPlugin extends GenericPlugin
{
    private const SCRIPT = '<script src="https://cookiechimp.com/widget/a2ZtgNB.js"></script>';

    /** @var bool Prevent duplicate inclusion during a single request. */
    private $injected = false;

    public function register($category, $path, $mainContextId = null)
    {
        $success = parent::register($category, $path, $mainContextId);
        if (!$success) {
            return false;
        }

        if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) {
            return true;
        }

        if ($this->getEnabled()) {
            Hook::add('TemplateManager::display', [$this, 'addCookieChimpHeader']);
        }

        return true;
    }

    /** Enable/disable once at the site level, regardless of current journal. */
    public function isSitePlugin()
    {
        return true;
    }

    public function getDisplayName()
    {
        return 'CookieChimp';
    }

    public function getDescription()
    {
        return 'Loads the CookieChimp consent widget across all OJS journals using a site-wide setting.';
    }

    /**
     * Inject into the frontend <head>, including site homepage and journal pages.
     * Runs before the template is rendered, following the PKP customHeader pattern.
     */
    public function addCookieChimpHeader($hookName, $params)
    {
        if ($this->injected) {
            return false;
        }

        $templateMgr = $params[0];
        $templateMgr->addHeader('cookieChimp', self::SCRIPT, ['contexts' => ['frontend']]);
        $this->injected = true;

        return false;
    }
}

if (!PKP_STRICT_MODE) {
    class_alias('\\APP\\plugins\\generic\\cookieChimp\\CookieChimpPlugin', '\\CookieChimpPlugin');
}
