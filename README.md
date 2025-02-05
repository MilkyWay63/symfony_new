
## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

Notes from Emiliana:
-generate needed cerfiticates for authentification for api endpoints

For testing use this instructions below:
https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/3-functional-testing.html

for postman, you can load post fixture. I have added register and login endpoints to create a user and generate token
postman collection is added Post API Collection
