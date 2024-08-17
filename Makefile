install:
	composer install

gendiff:
	./bin/gendiff

lint:
	phpcs --standard=PSR12 src/