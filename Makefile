bash:
	docker compose exec app bash

cache:
	bin/console cache:clear


up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose down \
	&& docker compose build \
	&& docker compose up -d

cicd:
	make composer
	make cs-fix
	make deptrac
	make di
	make phpunit
#	make psalm
	make rector
	make schema-validate

composer:
	composer normalize --diff --dry-run \
	&& composer validate \
	&& vendor/bin/composer-require-checker check --config-file=composer-require-checker.json \
#	&& vendor/bin/composer-unused \
	&& composer audit

cs-fix:
	./vendor/bin/php-cs-fixer fix src --dry-run --stop-on-violation -v

deptrac:
	vendor/bin/deptrac --config-file=deptrac.modules.yaml --cache-file=var/.deptrac.modules.cache
	vendor/bin/deptrac --config-file=deptrac.yaml --cache-file=var/.deptrac.cache

di:
	bin/console cache:clear --env=prod \
	&& bin/console lint:container --env=prod

phpunit:
	XDEBUG_MODE=coverage php ./vendor/bin/phpunit --colors=never --coverage-text --coverage-cobertura=coverage.cobertura.xml --log-junit phpunit-report.xml --do-not-cache-result

psalm:
	vendor/vimeo/psalm/psalm

rector:
	vendor/rector/rector/bin/rector --dry-run

schema-validate:
	bin/console doctrine:schema:validate --skip-sync
