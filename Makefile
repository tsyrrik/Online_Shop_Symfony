bash:
	docker compose exec app bash

build:
	docker compose down \
	&& docker compose build \
	&& docker compose up -d

cs-fix:
	vendor/vimeo/psalm/psalm
