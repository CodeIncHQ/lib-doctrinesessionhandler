# Doctrine Session Handler

---

:warning: The Doctrine session handler is now integrated in [`codeinc/lib-session`](https://github.com/CodeIncHQ/lib-session) since its version 2.0. This package is abandon and should not be used anymore for new developpements.

---

The library provides a session handler compatible with the native PHP [`SessionHandlerInterface`](http://php.net/manual/en/class.sessionhandlerinterface.php) interface. This session handler stores the session data in the database using [Doctrine ORM](http://www.doctrine-project.org/).

## Installation
This library is available through [Packagist](https://packagist.org/packages/codeinc/lib-doctrinesessionhandler) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/lib-doctrinesessionhandler
```

## Suggested library

We recommend the [`lib-session`](https://github.com/CodeIncHQ/lib-session) which provides a session manager compatible with [PSR-7](https://www.php-fig.org/psr/psr-7/) requests and [PSR-15](https://www.php-fig.org/psr/psr-15/) middlewares.

## License
This library is published under the MIT license (see the [LICENSE](https://github.com/CodeIncHQ/lib-session/blob/master/LICENSE) file). 

