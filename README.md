# Introduction

Custom Vtiger Asterisk Connector. Allows to easily develop additional logic for your Vtiger.

# System requirements

Apache/NginX

mod_rewrite

php5.6 or above

php-curl

mysql

# Installation

Install src/ as new apache virtual host with enabled .htaccess at any open port. Make sure that same port
and server host set in config.inc => recordsServer.

Try to start __> php src/service/Connector.php__

# Usage

there would be some sample use cases

# Credits

RVConnector build on __[PAMI](https://github.com/marcelog/PAMI)__, uses __[Monolog](https://github.com/Seldaek/monolog)__
for logging and __[Composer](https://getcomposer.org/doc/00-intro.md)__ for dependency management