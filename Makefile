install:
	composer install

gendiff:
	./bin/gendiff

lint:
	./vendor/bin/phpcs --standard=PSR12 src/