<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace MailForm\Service;

use Krystal\Application\View\ViewManagerInterface;

interface SiteServiceInterface
{
    /**
     * Sets configured view instance
     * 
     * @param \Krystal\Application\View\ViewManagerInterface $view
     * @return void
     */
    public function setView(ViewManagerInterface $view);

    /**
     * Renders the partial form by its id
     * 
     * @param string $id Form id
     * @return string
     */
    public function render($id);
}
