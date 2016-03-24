#!/usr/bin/env bash

if [ ${TRAVIS_BRANCH} == "master" ]; then
    export COMPOSER_ROOT_VERSION=dev-develop;
elif [ ${TRAVIS_BRANCH} == "develop" ]; then
    export COMPOSER_ROOT_VERSION=dev-develop;
elif [ ${TRAVIS_BRANCH} == *"release"* ]; then
    export COMPOSER_ROOT_VERSION=dev-${TRAVIS_BRANCH};
else
    export COMPOSER_ROOT_VERSION=${TRAVIS_BRANCH};
fi;

echo COMPOSER_ROOT_VERSION=${COMPOSER_ROOT_VERSION}

