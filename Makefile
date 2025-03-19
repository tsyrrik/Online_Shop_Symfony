bash:
	docker compose exec app bash

cache:
	app/bin/console cache:clear
up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose down \
	&& docker compose build \
	&& docker compose up -d

cicd: cs-fix phpunit composer psalm di_lint schema-validate deptrac comments_density rector

cs-fix:
	./app/vendor/bin/php-cs-fixer -v --config=app/.php-cs-fixer.dist.php fix --dry-run --stop-on-violation --diff   #проверяет весь проект

cs-fix-apply: #автоисправление
	./app/vendor/bin/php-cs-fixer -v --config=app/.php-cs-fixer.dist.php fix


phpunit:
	./app/vendor/bin/phpunit --configuration app/phpunit.xml.dist

composer:
	docker-compose exec app bash -c 'cd app && ( \
		composer normalize --diff --dry-run && \
		composer validate && \
        ./vendor/bin/composer-require-checker check --config-file=composer-require-checker.json && \
		composer audit && \
		composer check-platform-reqs \
	)'
#	vendor/bin/composer-unused && \ #выдает много пакетов позже пофикшу
						#composer require --dev bamarni/composer-bin-plugin   #для установки unesed
						#composer bin composer-unused require --dev icanhazstring/composer-unused


psalm:
	./app/vendor/bin/psalm --config=app/psalm.xml


di_lint:
	app/bin/console cache:clear --env=prod
	app/bin/console lint:container --env=prod

schema-validate:
	app/bin/console doctrine:schema:validate --skip-sync

deptrac:
	app/vendor/bin/deptrac --config-file=app/deptrac.modules.yaml --cache-file=app/var/.deptrac.modules.cache
	app/vendor/bin/deptrac --config-file=app/deptrac.directories.yaml --cache-file=app/var/.deptrac.directories.cache

comments_density:
	cd app && ./vendor/bin/comments_density analyze

rector:
	docker compose exec app bash -c 'cd app && vendor/rector/rector/bin/rector --dry-run'

rector-apply:
	docker compose exec app bash -c 'cd app && vendor/rector/rector/bin/rector'


