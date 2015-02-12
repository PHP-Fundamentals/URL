#!/bin/bash

dir=$(d=$(dirname "$0"); cd "$d" && pwd)

php ${dir}/composer.phar self-update
php ${dir}/composer.phar update
php ${dir}/composer.phar dumpautoload -o