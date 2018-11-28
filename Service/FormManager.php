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

use Cms\Service\HistoryManagerInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use MailForm\Storage\FormMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Security\Filter;

final class FormManager extends AbstractManager implements FormManagerInterface
{
    /**
     * Any compliant form mapper
     * 
     * @var \MailForm\Storage\FormMapperInterface
     */
    private $formMapper;

    /**
     * Web page manager to deal with slugs
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * History manager to keep track of latest actions
     * 
     * @var \Cms\Service\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \MailForm\Storage\FormMapperInterface $formMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(
        FormMapperInterface $formMapper,
        WebPageManagerInterface $webPageManager,
        HistoryManagerInterface $historyManager
    ){
        $this->formMapper = $formMapper;
        $this->webPageManager = $webPageManager;
        $this->historyManager = $historyManager;
    }

    /**
     * Returns a collection of switching URLs
     * 
     * @param string $id Form ID
     * @return array
     */
    public function getSwitchUrls($id)
    {
        return $this->formMapper->createSwitchUrls($id, 'MailForm', 'MailForm:Form@indexAction');
    }

    /**
     * Updates SEO states by associated form ids
     * 
     * @param array $pair
     * @return boolean
     */
    public function updateSeo(array $pair)
    {
        foreach ($pair as $id => $seo) {
            if (!$this->formMapper->updateSeoById($id, $seo)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $form)
    {
        $entity = new VirtualEntity();
        $entity->setId($form['id'], VirtualEntity::FILTER_INT)
                ->setLangId($form['lang_id'], VirtualEntity::FILTER_INT)
                ->setWebPageId($form['web_page_id'], VirtualEntity::FILTER_INT)
                ->setTitle($form['title'], VirtualEntity::FILTER_HTML)
                ->setName($form['name'], VirtualEntity::FILTER_HTML)
                ->setDescription($form['description'], VirtualEntity::FILTER_SAFE_TAGS)
                ->setSeo($form['seo'], VirtualEntity::FILTER_BOOL)
                ->setSlug($form['slug'])
                ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
                ->setPermanentUrl('/module/mail-form/'.$entity->getId())
                ->setTemplate($form['template'], VirtualEntity::FILTER_HTML)
                ->setKeywords($form['keywords'], VirtualEntity::FILTER_HTML)
                ->setFlash($form['flash'], VirtualEntity::FILTER_HTML)
                ->setMetaDescription($form['meta_description'], VirtualEntity::FILTER_HTML)
                ->setMessage($form['message'])
                ->setSubject($form['subject'], VirtualEntity::FILTER_HTML)
                ->setCaptcha($form['captcha'], VirtualEntity::FILTER_BOOL);

        if (isset($form['field_count'])) {
            $entity->setFieldCount($form['field_count'], VirtualEntity::FILTER_INT);
        }

        return $entity;
    }

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->formMapper->getLastId();
    }

    /**
     * Delete by collection of ids
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        $this->formMapper->deletePage($ids);
        $this->track('Batch removal of %s mail forms', count($ids));

        return true;
    }

    /**
     * Deletes a form by its associated id
     * 
     * @param string $id Form id
     * @return boolean
     */
    public function deleteById($id)
    {
        #$name = $this->formMapper->fetchNameById($id);

        if ($this->formMapper->deletePage($id)) {
            #$this->track('Mail form "%s" has been removed', $name);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Fetches all form entities
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->formMapper->fetchAll());
    }

    /**
     * Fetches form entity by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations === true) {
            return $this->prepareResults($this->formMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->formMapper->fetchById($id, false));
        }
    }

    /**
     * Save a page
     * 
     * @param array $input
     * @return boolean
     */
    private function savePage(array $input)
    {
        $data = ArrayUtils::arrayWithout($input['form'], array('slug'));
        return $this->formMapper->savePage('MailForm', 'MailForm:Form@indexAction', $data, $input['translation']);
    }

    /**
     * Adds a form
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function add(array $input)
    {
        #$this->track('Mail form "%s" has been created', $form['name']);
        return $this->savePage($input);
    }

    /**
     * Updates a form
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        #$this->track('Mail form "%s" has been updated', $form['name']);
        return $this->savePage($input);
    }

    /**
     * Tracks activity
     * 
     * @param string $message
     * @param string $placeholder
     * @return boolean
     */
    private function track($message, $placeholder = '')
    {
        return $this->historyManager->write('MailForm', $message, $placeholder);
    }
}
