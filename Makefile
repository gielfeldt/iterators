all: clean test coverage

clean:
	rm -rf build/artifacts/*

lint:
	find src -name "*.php" -print0 | xargs -0 -n1 php -l

cs: 
	phpcs --standard=PSR2 src/

test: lint
	vendor/bin/phpunit

coverage: lint
	vendor/bin/phpunit --coverage-html=build/artifacts/coverage

coverage-show:
	open build/artifacts/coverage/index.html
