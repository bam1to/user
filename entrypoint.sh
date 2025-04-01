#!/bin/sh

composer install --prefer-dist --no-scripts --no-progress

exec "$@"