bash:
	docker compose exec app bash

cache:
	bin/console cache:clear


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
	make deptrac
	make di
	make psalm
	make rector
	make schema-validate

cs-fix:
	./vendor/bin/php-cs-fixer fix src --dry-run --stop-on-violation

phpunit:
	./vendor/bin/phpunit

composer:
	composer normalize --diff --dry-run \
	&& composer validate \
	&& vendor/bin/composer-require-checker check --config-file=composer-require-checker.json \
#	&& vendor/bin/composer-unused \
						#composer require --dev bamarni/composer-bin-plugin   #для установки unesed
						#composer bin composer-unused require --dev icanhazstring/composer-unused
	&& composer audit

deptrac:
	vendor/bin/deptrac --config-file=deptrac.modules.yaml --cache-file=var/.deptrac.modules.cache
	vendor/bin/deptrac --config-file=deptrac.yaml --cache-file=var/.deptrac.cache

di:
	bin/console cache:clear --env=prod \
	&& bin/console lint:container --env=prod

psalm:
	vendor/vimeo/psalm/psalm

rector:
	vendor/rector/rector/bin/rector --dry-run

schema-validate:
	bin/console doctrine:schema:validate --skip-sync

