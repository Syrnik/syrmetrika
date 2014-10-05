# Плагин для Shopcript5 для передачи параметров заказа в Яндекс.Метрику

Плагин позволяет назначить цели для посещения страниц корзины, и этапов
оформления заказа: ввода контактной информации, выбора способа доставки,
выбора способа оплаты, подтверждения заказа и передачи информации о
составе заказа на заключительном этапе — странице с сообщением, что заказ принят.

Плагин работает как с синхронной, так и с асинхронной загрузкой кода счетчика
Метрики и протестирован со всеми стандартными темами оформления.

Обработку любой цели можно отключить, просто оставив пустым идентификатор
события в настройках плагина.

## Настройка

Для работы необходимо указать название переменной с кодом счетчика Метрики.
Обычно переменная называется `yaCounterXXXXX` где 'XXXXX' это номер счетчика.
Посмотреть точное название можно в коде счетчика.

Для каждого из шагов (корзина и т.д.), который является целью, необходимо
указать идентификатор цели. Яндекс не рекомендует называть идентификатор также,
как адрес страницы — придумайте какое-нибудь свое название, например для корзины
'IN_CART' и. д.

Также для каждого события необходимо создать цель типа «Событие» в настройках
Яндекс.Метрики. [Подробнее о создании целей в помощи по Яндекс.Метрике](https://help.yandex.ru/metrika/general/goals.xml#event]).
Для целей «Корзина» и «Заказ оформлен» цели необходимо сконфигурировать особым
образом. [Подробнее о передаче параметров интернет-магазинов в помощи по Яндекс.Метрике](https://help.yandex.ru/metrika/content/e-commerce.xml).

Редактировать код счетчика в соответствии с рекомендациями Яндекса не нужно,
нужны только идентификаторы целей.


## Примечания

Яндекс.Метрика иногда притормаживает с обработкой результатов.

Если возникли трудности, не стесняйтесь обратиться за поддержкой.
