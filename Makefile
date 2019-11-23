ifndef u
u:=sotatek
endif

ifndef env
env:=dev
endif

OS:=$(shell uname)

docker-start:
	docker-compose up -d

lint-js:
	npm run test

docker-restart:
	docker-compose down
	make docker-start

docker-connect:
	docker exec -it bitcastle_admin bash

init-app:
	cp .env.example .env
	mkdir -p database/seeds
	mkdir -p database/factories
	mkdir -p database/migrations
	composer install
	php artisan key:generate
	php artisan passport:key --force
	rm -rf node_modules
	npm install

docker-init:
	docker network connect bitcastle_backend_default bitcastle_admin
	docker exec -it bitcastle_admin make init-app
	docker exec -it bitcastle_admin chmod -R 777 storage bootstrap/cache

start:
	php artisan serve

log:
	tail -f storage/logs/laravel.log

log-daily:
	tail -f "./storage/logs/laravel-$(shell date +"%Y-%m-%d").log"

test-js:
	npm test

test-php:
	vendor/bin/phpcs --standard=phpcs.xml && vendor/bin/phpmd app text phpmd.xml

build:
	npm run dev

watch:
	docker exec -it bitcastle_admin npm run watch

autoload:
	composer dump-autoload

cache:
	php artisan cache:clear && php artisan view:clear && php artisan config:clear

docker-cache:
	docker exec bitcastle_admin make cache

route:
	php artisan route:list

generate-master:
	php bin/generate_master.php $(lang)

update-master:
	php artisan master:update $(lang)
	make cache

deploy:
	ssh $(u)@$(h) "mkdir -p $(dir)"
	rsync -avhzL --delete \
				--no-perms --no-owner --no-group \
				--exclude .git \
				--exclude .idea \
				--exclude .env \
				--exclude laravel-echo-server.json \
				--exclude storage/*.key \
				--exclude node_modules \
				--exclude /vendor \
				--exclude bootstrap/cache \
				--exclude storage/logs \
				--exclude storage/framework \
				--exclude storage/app \
				--exclude public/storage \
				--exclude database/migrations/erc20 \
				--exclude echo.json \
				. $(u)@$(h):$(dir)/

deploy2:
	ssh root@$(h) "mkdir -p $(dir)"
	rsync -avhzL --delete \
				--no-perms --no-owner --no-group \
				--exclude .git \
				--exclude .idea \
				--exclude .env \
				--exclude laravel-echo-server.json \
				--exclude storage/*.key \
				--exclude node_modules \
				--exclude /vendor \
				--exclude bootstrap/cache \
				--exclude storage/logs \
				--exclude storage/framework \
				--exclude storage/app \
				--exclude public/storage \
				--exclude database/migrations/erc20 \
				--exclude echo.json \
				. root@$(h):$(dir)/

deploy-dev:
	make deploy h=192.168.1.203 dir=/var/www/bitcastle-admin$(n)

deploy-214:
	make deploy2 h=192.168.1.214 dir=/var/www/bitcastle_admin
	ssh root@192.168.1.214 "cd /var/www/bitcastle_admin && make cache"

deploy-staging:
	make deploy u=centos h=13.112.166.177 dir=/var/www/bitcastle_admin
	ssh centos@13.112.166.177 "cd /var/www/bitcastle_admin\
		&& composer install\
		&& npm install\
		&& composer dump-autoload\
		&& make cache\
		&& sudo supervisorctl restart all\
		&& npm run dev\
		"

deploy-prod:
	make deploy u=centos h=54.169.142.245 dir=/var/www/bitcastle_admin
	ssh centos@54.169.142.245 "cd /var/www/bitcastle_admin\
		&& composer install\
		&& npm install\
		&& composer dump-autoload\
		&& make cache\
		&& sudo supervisorctl restart all\
		&& npm run dev\
		"


compile:
	ssh $(u)@$(h) "cd $(dir) && npm install && composer install && make cache && make autoload && npm run production"

deploy-production-full:
	make deploy server=52.78.104.238 u=root dir=/root/trading
	make compile server=52.78.104.238 u=root dir=/root/trading
	ssh root@52.78.104.238 "rsync -avhzL --delete --no-perms --no-owner --no-group \
																	--exclude .env \
																	--exclude public/storage \
																	--exclude bootstrap/cache \
																	--exclude storage/logs \
																	--exclude storage/framework \
																	--exclude storage/app \
																	/root/trading/* /var/www/trading/"
	ssh root@52.78.104.238 "cd /var/www/trading/ && make cache"

localization:
	docker exec -it bitcastle_admin php artisan localization:sort
