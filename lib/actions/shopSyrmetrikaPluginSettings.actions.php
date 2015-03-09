<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version 2.0.0
 * @copyright (c) 2014,2015 Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

/**
 * Backend Settings
 *
 * @package Syrmetrika.controller
 */
class shopSyrmetrikaPluginSettingsActions extends waViewActions
{
    protected $template_folder = 'templates/actions/settings/';

    /** @var shopSyrmetrikaPlugin */
    private $Plugin;

    private $response = array();
    private $errors = array();

    public function defaultAction()
    {
        $this->view->assign('settlements', $this->getSettlements());
    }

    public function settingsAction()
    {
        $this->respondAs('json');
        $settlement = $this->getRequest()->request('settlement', NULL, waRequest::TYPE_STRING_TRIM);
        if (!$settlement) {
            throw new waException('Не указана витрина', 500);
        }

        if ($this->getRequest()->method() == 'get') {
            $this->response = $this->Plugin->getSettlementSetting($settlement);
        } elseif ($this->getRequest()->method() == 'post') {
            if ($this->Plugin->saveSettlementSetting($settlement, $this->getRequest()->post('setting', array(), waRequest::TYPE_ARRAY))) {
                $this->response = array('Настройки сохранены');
            } else {
                $this->errors = array('Ошибка сохранения настроек');
            }
        } else {
            throw new waException('Method Not Allowed', 405);
        }
    }

    /**
     * Расширенный метод, работающий и с JSON-ответами, и с обычными
     */
    public function display()
    {
        if ($this->respondAs() == 'application/json') {
            if ($this->errors) {
                $this->view->assign('data', array('status' => 'fail', 'errors' => $this->errors));
            } else {
                $this->view->assign('data', array('status' => 'ok', 'data' => $this->response));
            }
        }
        parent::display();
    }

    /**
     * @throws waException
     */
    protected function preExecute()
    {
        $this->Plugin = wa('shop')->getPlugin('syrmetrika');
        parent::preExecute();
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        $plugin_root = $this->getPluginRoot();

        if ($this->template === NULL) {
            if ($this->respondAs() === 'application/json') {
                return $plugin_root . 'templates/json.tpl';
            }
            $template = ucfirst($this->action);
        } else {
            if (strpbrk($this->template, '/:') !== FALSE) {
                return $this->template;
            }
            $template = $this->template;
        }

        return $plugin_root . $this->template_folder . $template . $this->view->getPostfix();
    }

    /**
     * @param string $type
     * @return string
     */
    protected function respondAs($type = NULL)
    {
        if ($type !== NULL) {
            if ($type == 'json') {
                $type = 'application/json';
            }
            $this->getResponse()->addHeader('Content-type', $type);
        }

        return $this->getResponse()->getHeader('Content-type');
    }

    /**
     * Возвращает массив URL поселений магазина в виде строк типа 'domain.com/shop/*'
     *
     * @return array
     */
    private function getSettlements()
    {
        $domain_routes = wa()->getRouting()->getByApp('shop');
        $settlements = array();

        foreach ($domain_routes as $domain => $routes) {
            foreach ($routes as $route) {
                $settlements[] = $domain . '/' . $route['url'];
            }
        }

        return $settlements;
    }
}