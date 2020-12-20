build:
	@cp -n .env.example .env
	docker-compose pull
	docker-compose build --pull

test: test74 test73 test72 test71 test70 test56
test74:
	docker-compose build --pull php74
	docker-compose run php74 vendor/bin/phpunit
	docker-compose down
test73:
	docker-compose build --pull php73
	docker-compose run php73 vendor/bin/phpunit
	docker-compose down
test72:
	docker-compose build --pull php72
	docker-compose run php72 vendor/bin/phpunit
	docker-compose down
test71:
	docker-compose build --pull php71
	docker-compose run php71 vendor/bin/phpunit
	docker-compose down
test70:
	docker-compose build --pull php70
	docker-compose run php70 vendor/bin/phpunit
	docker-compose down
test56:
	docker-compose build --pull php56
	docker-compose run php56 vendor/bin/phpunit
	docker-compose down

clean:
	docker-compose down
	sudo rm -rf tests/runtime/*
	sudo rm -rf composer.lock
	sudo rm -rf vendor/

clean-all: clean
	sudo rm -rf tests/runtime/.composer*
