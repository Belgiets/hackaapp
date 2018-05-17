## Project setup ##
1. setup composer dependencies - composer install
1. add config parameters to .env file or env variables
1. create db - bin/console doctrine:database:create
1. update db schema - bin/console doctrine:schema:update --force
1. setup frontend
	- install deps:  
	npm install
	- compile assets once:  
	./node_modules/.bin/gulp
	- recompile assets automatically when files change:  
	./node_modules/.bin/gulp watch
	- compile assets, but also minify & optimize them:  
	./node_modules/.bin/gulp --env production
1. generate fixtures - bin/console hautelook:fixtures:load
1. add superadmin user - bin/console app:create-user %email% %password%