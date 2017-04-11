all: clean test coverage

clean:
	rm -rf build/artifacts/*

test:
	vendor/bin/phpunit --testsuite=iterators $(TEST)

coverage:
	vendor/bin/phpunit --testsuite=iterators --coverage-html=build/artifacts/coverage $(TEST)

coverage-show:
	open build/artifacts/coverage/index.html
