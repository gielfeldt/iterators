all: clean test coverage

clean:
	rm -rf build/artifacts/*

test:
	phpunit --testsuite=iterators $(TEST)

coverage:
	phpunit --testsuite=iterators --coverage-html=build/artifacts/coverage $(TEST)

coverage-show:
	open build/artifacts/coverage/index.html
