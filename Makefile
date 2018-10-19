code=

load:
	php console load ../data.csv

pick-one:
	php console pick-one ../data.json --country-code=$(code)

add-user:
	php console add-user ../data.json

install:
	php bin/composer.phar install

lint:
	php vendor/squizlabs/php_codesniffer/bin/phpcs -s src/
