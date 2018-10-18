
run:
	php console app:load ../data.csv

install:
	php bin/composer.phar install

lint:
	php vendor/squizlabs/php_codesniffer/bin/phpcs -s src/
