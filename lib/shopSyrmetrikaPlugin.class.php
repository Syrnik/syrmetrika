<?php

/**
 * @package Syrmetrika
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @version 2.0.0
 * @copyright (c) 2014,2015 Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */
class shopSyrmetrikaPlugin extends shopPlugin
{
    /** @var waAppSettingsModel */
    private $Settings;

    /**
     * @param array $info
     */
    public function __construct($info)
    {
        $this->Settings = new waAppSettingsModel();

        parent::__construct($info);
    }

    /**
     * Hook frontend_checkout
     *
     * @param array $param
     * @return string HTML code for hook
     */
    public function frontendCheckout($param)
    {
        // Известные нам шаги, которые отрабатываем
        $steps = array('contactinfo', 'shipping', 'payment', 'confirmation', 'success');

        if (!is_array($param) || !isset($param['step']) || !in_array($param['step'], $steps)) {
            return "";
        }

        // Нет названия счетчика
        $yacounter = trim($this->getSettings('counter_name'));
        if (!$yacounter) {
            return "";
        }

        // Это возврат после оплаты. Мы уже все отправили в Метрику
        if (($param['step'] === 'success') && waRequest::get('order_id')) {
            return "";
        }

        // Нет названия цели
        $target = trim($this->getSettings("target_{$param['step']}"));
        if (!$target) {
            return "";
        }

        $debug = waSystem::getInstance('wa-system')->getConfig()->isDebug();

        // Параметры заказа для успешного оформления, остальным ничего
        $yaparams = $param['step'] === 'success' ? $this->checkoutSuccess() : array();

        $view = waSystem::getInstance()->getView();
        $template = $this->path . '/templates/hook.html';

        $view->assign(compact(array('yacounter', 'yaparams', 'target', 'debug')));

        return $view->fetch($template);
    }

    /**
     * Hook frontend_cart
     *
     * @param mixed $param
     * @return string HTML code for hook
     */
    public function frontendCart($param)
    {
        $yacounter = $this->getSettings('counter_name');
        $yaparams = array();
        $target = $this->getSettings("target_cart");
        $debug = waSystem::getInstance('wa-system')->getConfig()->isDebug();

        if ($yacounter && $target) {
            $view = waSystem::getInstance()->getView();
            $template = $this->path . '/templates/hook.html';

            $view->assign(compact('yacounter', 'yaparams', 'target', 'debug'));

            return $view->fetch($template);
        }

        return "";
    }

    /**
     * Возвращает настройки плагина для указанного поселения. Если указан параметр $name, то возвращает
     * только параметр с этим названием
     *
     * @param string $settlement
     * @param string $name
     * @return string|array
     */
    public function getSettlementSetting($settlement, $name = NULL)
    {
        $settings = array(
            'counter_name'        => '',
            'target_cart'         => '',
            'target_contactinfo'  => '',
            'target_shipping'     => '',
            'target_payment'      => '',
            'target_confirmation' => '',
            'target_success'      => ''
        );

        $all_settings = json_decode($this->Settings->get($this->getSettingsKey(), 'settings', json_encode(array())));

        if (array_key_exists($settlement, $all_settings) && is_array($all_settings[$settlement])) {
            array_merge($settings, $all_settings[$settlement]);
        }

        if ($name) {
            if (array_key_exists($name, $settings)) {
                return $settings['name'];
            } else {
                return '';
            }
        }

        return $settings;
    }

    /**
     * @param string $settlement
     * @param array $settings
     * @return bool
     */
    public function saveSettlementSetting($settlement, $settings)
    {
        $default_settings = array(
            'counter_name'        => '',
            'target_cart'         => '',
            'target_contactinfo'  => '',
            'target_shipping'     => '',
            'target_payment'      => '',
            'target_confirmation' => '',
            'target_success'      => ''
        );

        $current_settings = array();

        $all_settings = json_decode($this->Settings->get($this->getSettingsKey(), 'settings', json_encode(array())));

        if (array_key_exists($settlement, $all_settings) && is_array($all_settings[$settlement])) {
            $current_settings = array_merge($default_settings, $all_settings[$settlement]);
        } else {
            $current_settings = $default_settings;
        }

        $all_settings[$settlement] = array_merge($current_settings, $settings);

        return $this->Settings->set($this->getSettingsKey(), 'settings', json_encode($all_settings));
    }

    /**
     * Returns additional info about order for Yandex
     *
     * @return array Additional parameters for YaCounter
     */
    private function checkoutSuccess()
    {
        // Еще разок проверим
        if (waRequest::get('order_id')) {
            return array();
        }

        $order_id = waSystem::getInstance()->getStorage()->get('shop/order_id');
        if (!$order_id) { // Что-то пошло не так. Должен он тут быть!
            return array();
        }

        $order_model = new shopOrderModel();
        $order = $order_model->getById($order_id);
        if ($order) {
            $order['_id'] = $order['id'];
        }

        $order_params_model = new shopOrderParamsModel();
        $order['params'] = $order_params_model->get($order_id);
        $order_items_model = new shopOrderItemsModel();
        $order['items'] = $order_items_model->getByField('order_id', $order_id, TRUE);
        $order['id'] = shopHelper::encodeOrderId($order_id);

        return $this->getYaparams($order);
    }

    /**
     * Extract needed parameters from order
     *
     * @param array $order
     * @return array
     */
    private function getYaparams($order)
    {
        $yaparams = array(
            "order_id"    => $order['id'],
            "order_price" => $this->getBasePrice($order["total"], $order["currency"]),
            "currency"    => $order["currency"]
        );

        foreach ($order["items"] as $item) {
            $ya_item = array(
                "name"     => $item["name"],
                "price"    => $this->getBasePrice($item["price"], $order["currency"]),
                "quantity" => intval($item["quantity"])
            );

            if ($item["type"] === "product") {
                $ya_item["id"] = $item["sku_id"];
            }

            $yaparams["goods"][] = $ya_item;
        }

        return $yaparams;
    }

    /**
     * @param float $price
     * @param string $currency
     * @return float
     * @throws waException
     */
    private function getBasePrice($price, $currency)
    {
        return floatval(str_replace(",", ".", shop_currency($price, $currency, waSystem::getInstance('shop')->getConfig()->getCurrency(TRUE), FALSE)));
    }
}
