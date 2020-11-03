```bash
composer install
```

Copy .env to .env.local and setup database

```bash
./bin/console doctrine:migrations:migrate --no-interaction
./bin/console doctrine:fixtures:load
```

```bash
yarn install
yarn dev
```
or
```bash
yarn build
```
