## 安装组件
```sh
composer require exewen/logger
```
## 复制配置
```sh
cp -rf ./publish/exewen /your_project/config
``` 
## 初始化
```php
!defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));
``` 
## 写入日志
```php
# 初始化DI
$app = new Container();
$app->setProviders([LoggerProvider::class]);
$this->app = $app;

/** @var LoggerInterface $logger */
$logger = $this->app->get(LoggerInterface::class);
$logger->info("info日志");
$logger->debug("debug日志");
$logger->error("error日志");
```
## 使用 facades
```sh
composer require exewen/facades
```
```php
# info
LoggerFacade::info("info日志");
# error
LoggerFacade::error("error日志");
# warning
LoggerFacade::warning("warning日志");
# notice
LoggerFacade::notice("notice日志");
# debug
LoggerFacade::debug("debug日志");
# request
LoggerFacade::request("request日志");
```