<?php
/**
 * @package Syrmetrika
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

class shopSyrmetrikaPlugin extends shopPlugin
{

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

        if(!is_array($param) || !isset($param['step']) || !in_array($param['step'], $steps)) {
            return "";
        }

        // Нет названия счетчика
        $yacounter = trim($this->getSettings('counter_name'));
        if(!$yacounter) {
            return "";
        }

        // Это возврат после оплаты. Мы уже все отправили в Метрику
        if(($param['step'] === 'success') && waRequest::get('order_id')) {
            return "";
        }

        // Нет названия цели
        $target = trim($this->getSettings("target_{$param['step']}"));
        if(!$target) {
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

        if($yacounter && $target) {
            $view = waSystem::getInstance()->getView();
            $template = $this->path . '/templates/hook.html';

            $view->assign(compact('yacounter', 'yaparams', 'target', 'debug'));

            return $view->fetch($template);
        }

        return "";
    }

    /**
     * Returns additional info about order for Yandex
     *
     * @return array Additional parameters for YaCounter
     */
    private function checkoutSuccess()
    {
        // Еще разок проверим
        if(waRequest::get('order_id')) {
            return array();
        }

        $order_id = waSystem::getInstance()->getStorage()->get('shop/order_id');
        if(!$order_id) { // Что-то пошло не так. Должен он тут быть!
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
        $order['items'] = $order_items_model->getByField('order_id', $order_id, true);
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
            "order_id" => $order['id'],
            "order_price" => $this->getBasePrice($order["total"], $order["currency"]),
            "currency" => $order["currency"]
        );

        foreach($order["items"] as $item) {
            $ya_item=array();

            if($item["type"] === "product") {
                $ya_item["id"]=$item["sku_id"];
            }
            $ya_item["name"] = $item["name"];
            $ya_item["price"] = $this->getBasePrice($item["price"], $order["currency"]);
            $ya_item["quantity"] = intval($item["quantity"]);

            $yaparams["goods"][] = $ya_item;
        }

        return $yaparams;
    }

    private function getBasePrice($price, $currency)
    {
        return floatval(str_replace(",", ".", shop_currency($price, $currency, waSystem::getInstance('shop')->getConfig()->getCurrency(TRUE), FALSE)));
    }
}
