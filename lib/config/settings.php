<?php
/**
 * Configuration parameters
 *
 * @package Syrmetrika
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

return array(
    "counter_name" => array(
        "title" => "Название счетчика",
        "description" => "Название переменной счетчика Метрики. Обычно выглядик как yaCounterXXXXX, где XXXXX цифры",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_cart" => array(
        "title" => "Название цели «Корзина»",
        "description" => "Настроенное название цели для посещения страницы <b>«Корзина»</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_contactinfo" => array(
        "title" => "Название цели «Контактная Информация»",
        "description" => "Настроенное название цели для посещения страницы оформления заказа с вводом <b>контактной информации</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_shipping" => array(
        "title" => "Название цели «Выбор доставки»",
        "description" => "Настроенное название цели для посещения страницы оформления заказа с <b>выбором способа доставки</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_payment" => array(
        "title" => "Название цели «Выбор оплаты»",
        "description" => "Настроенное название цели для посещения страницы оформления заказа с <b>выбором способа оплаты</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_confirmation" => array(
        "title" => "Название цели «Подтверждение заказа»",
        "description" => "Настроенное название цели для посещения страницы оформления заказа с <b>подтверждением заказа</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
    "target_success" => array(
        "title" => "Название цели «Заказ оформлен»",
        "description" => "Настроенное название цели для посещения страницы <b>успешным окончанием оформления заказа</b>. Оставьте это поле пустым, если не надо учитывать достижение этой цели.",
        "value" => "",
        "control_type" => waHtmlControl::INPUT
    ),
);