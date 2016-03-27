
build:frontend
	composer install --prefer-dist -o --no-dev


frontend:
	bower install
	npm install
	gulp

