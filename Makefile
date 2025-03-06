bash:
	docker compose exec app bash

cache:
	app/bin/console cache:clear
up:
	docker compose up -docker

down:
	docker compose down

build:
	docker compose down \
	&& docker compose build \
	&& docker compose up -d

cicd:
	make cs-fix
	make phpunit
	make composer
	make psalm
	make di_lint
	make schema-validate
	make deptrac
	make comments_density
	make rector

cs-fix:
#	./vendor/bin/php-cs-fixer fix src --dry-run --stop-on-violation   #папка src
	./app/vendor/bin/php-cs-fixer -v --config=app/.php-cs-fixer.dist.php fix --dry-run --stop-on-violation --diff   #проверяет весь проект

phpunit:
	./app/vendor/bin/phpunit --configuration app/phpunit.xml.dist

composer:
	cd app && ( \
		composer normalize --diff --dry-run && \
		composer validate && \
		vendor/bin/composer-require-checker check --config-file=composer-require-checker.json && \
		composer audit && \
		composer check-platform-reqs \
	)
#	vendor/bin/composer-unused && \ #выдает много пакетов позже пофикшу
						#composer require --dev bamarni/composer-bin-plugin   #для установки unesed
						#composer bin composer-unused require --dev icanhazstring/composer-unused


psalm:
	./app/vendor/bin/psalm --config=app/psalm.xml


di_lint:
	bin/console cache:clear --env=prod
	bin/console lint:container --env=prod

schema-validate:
	bin/console doctrine:schema:validate --skip-sync

deptrac:
	vendor/bin/deptrac --config-file=deptrac.modules.yaml --cache-file=var/.deptrac.modules.cache
	vendor/bin/deptrac --config-file=deptrac.directories.yaml --cache-file=var/.deptrac.directories.cache

comments_density:
	vendor/bin/comments_density analyze

rector:
	vendor/rector/rector/bin/rector --dry-run

