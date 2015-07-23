Вхідні скрипти
==============

Вхідні скрипти це перша ланка в процесі початкового завантаження додатку. Додаток (веб-додаток або консольний додаток)
має єдиний вхідний скрипт. Кінцеві користувачі роблять запити до вхідного скрипту, який створює екземпляри додатка та
перенаправляє запит до них.

Вхідні скрипти для веб-додатків повинні бути збережені в директоріях, доступних через веб, таким чином, вони можуть бути
доступними кінцевим користувачам. Зазвичай вони називаються `index.php`, але також можуть використовуватись й інші
імена, які можуть бути розпізнані веб-серверами.

Вхідні скрипти для консольних додатків зазвичай розміщенні у [базовій директорії](structure-applications.md)
додатку і мають назву `yii` (з суфіксом `.php`). Вони повинні мати права на виконання, щоб користувачі мали змогу
запускати консольні додатки через команду `./yii <маршрут> [аргументи] [опції]`.

Вхідні скрипти в основному виконують наступну роботу:

* Визначають глобальні константи;
* Реєструють автозавантажувач класів [Composer](https://getcomposer.org/doc/01-basic-usage.md#autoloading);
* Підключають файл класу [[Yii]];
* Завантажують конфігурацію додатка;
* Створюють і налаштовують екземпляр [додатка](structure-applications.md);
* Викликають метод [[yii\base\Application::run()]] додатка для обробки вхідного запиту.


## Веб-додатки <span id="web-applications"></span>

Нижче наведений код вхідного скрипту для [базового шаблону проекту](start-installation.md).

```php
<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// реєстрація автозавантажувача класів Composer
require(__DIR__ . '/../vendor/autoload.php');

// підключення файлу класу Yii
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// завантаження конфігурації додатка
$config = require(__DIR__ . '/../config/web.php');

// створення, конфігурація та виконання додатка
(new yii\web\Application($config))->run();
```


## Консольні додатки <span id="console-applications"></span>

Нижче наведений аналогічний код вхідного скрипту консольного додатка:

```php
#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);

// реєстрація автозавантажувача класів Composer
require(__DIR__ . '/vendor/autoload.php');

// підключення файлу класу Yii
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

// завантаження конфігурації додатка
$config = require(__DIR__ . '/config/console.php');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
```


## Визначення констант <span id="defining-constants"></span>

Вхідні скрипти є найкращим місцем для оголошення глобальних констант. Yii підтримує наступні три константи:

* `YII_DEBUG`: вказує чи працює додаток у режимі налагодження ("debug mode"), перебуваючи у якому, додаток
  буде зберігати більше інформації в журналі та покаже більш детальний стек викликів при отриманні виключення. З цієї причини,
  режим налагодження повинен використовуватись здебільшого в процесі розробки. За замовчуванням значення `YII_DEBUG` дорівнює `false`.
* `YII_ENV`: вказує в якому середовищі працює додаток. Дана тема детально розглянута у розділі 
  [Конфігурації](concept-configurations.md#environment-constants). За замовчуванням значення `YII_ENV` дорівнює
  `'prod'`, яке означає, що додаток працює у робочому ("production") середовищі.
* `YII_ENABLE_ERROR_HANDLER`: вказує чи потрібно увімкнути наявний у Yii обробник помилок.
  За замовчуванням значення даної константи дорівнює `true`.

При визначенні константи, розробники фреймворку зазвичай використовують код подібний до наступного:

```php
defined('YII_DEBUG') or define('YII_DEBUG', true);
```

який рівнозначний коду, наведеному нижче:

```php
if (!defined('YII_DEBUG')) {
    define('YII_DEBUG', true);
}
```

Перший варіант є більш коротким і зрозумілим.

Константи мають бути визначені якомога раніше, на самому початку вхідного скрипту, щоб вони могли вплинути на решту
PHP-файлів, які будуть підключатись.