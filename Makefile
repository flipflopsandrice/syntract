all: syntract

syntract: sync extract

sync:
	@php index.php --execute sync

extract:
	@php index.php --execute extract

test: phpunit

install: composer-update

composer-update:
	@composer update

phpunit:
	@vendor/bin/phpunit test/

phpcs:
	@composer cs
