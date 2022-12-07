# Copy .env if not exists
if [ ! -f ./.env ]; then
    echo "No .env file detected. Copying .env.example"
    cp ./.env.example ./.env
fi

echo "Building project for deployment"

# install composer dependencies
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v $(pwd):/opt \
  -w /opt \
  laravelsail/php80-composer:latest \
  composer install --ignore-platform-reqs

vendor/bin/sail up -d
vendor/bin/sail composer install
vendor/bin/sail artisan storage:link
vendor/bin/sail npm install
vendor/bin/sail npm run prod
vendor/bin/sail down
    