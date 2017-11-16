#SwitchMedia Programming Challenge

## Running it via Docker image from Docker Hub
It's the most convinient way to use it. Just run:

`docker run -i therosco/switchmedia:latest animation 12:00 https://pastebin.com/raw/cVyp3McN`

Where `animation 12:00 https://pastebin.com/raw/cVyp3McN` are 3 obvious arguments of **a genre**, **time** 
and **an input file/stream**.

## Building a docker image at home (for the greater good of security)

1. Clone the repository to a folder on your device
2. Check all the code for a possible backdoor 
3. Run `docker build -t therosco/switchmedia:latest ./` to build a docker image
3. Run `docker run -i therosco/switchmedia:latest animation 12:00 https://pastebin.com/raw/cVyp3McN`

## A fully manual way for a true enthusiast
1. Clone the repository to a folder on your device
2. Assess all the code, ensure youve got [Composer](https://getcomposer.org)
3. Run `composer -d=./src -vvv  install`
4. Run unit tests `php src/vendor/bin/phpunit --verbose  -c src/phpunit.xml` to be sure in the code.
5. Run `php src/app.php movie:recommend animation 12:00 ./src/test/data/data.json` to play with code live.