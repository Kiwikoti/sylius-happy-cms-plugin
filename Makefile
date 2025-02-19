.DEFAULT_GOAL := help
SHELL=/bin/bash

-include .makefile/composer.mk
-include .makefile/global.mk

###
### VERSIONS
### ¯¯¯

SYLIUS_VERSION=2.0.0
SYMFONY_VERSION=7.1
NODE_VERSION=20
COMPOSE_PROJECT_NAME=sylius-happy-cms-plugin

PLUGIN_NAME=agence-adeliom/sylius-happy-cms-plugin
PLUGIN_DIR=lib/sylius-happy-cms-plugin
PLUGIN_NAMESPACE=SyliusHappyCMSPlugin
PLUGIN_URL=git@github.com:agence-adeliom/sylius-happy-cms-plugin.git
PLUGIN_ALIAS=sylius_happy_cms

CRUD_PLUGIN_NAMESPACE=SyliusEasyCrudPlugin
CRUD_PLUGIN_URL=git@github.com:agence-adeliom/sylius-easy-crud-plugin.git
CRUD_PLUGIN_ALIAS=sylius_easy_crud

DOCKER_USER ?= "$(shell id -u):$(shell id -g)"
ENV ?= "dev"
DOCKER_PHP_PORT ?= 8051
DOCKER_MYSQL_PORT ?= 63502

###
### DEV
### Commands to install sylius standard version and this plugin automatically
### ¯¯¯¯¯¯¯¯¯¯¯

-include .makefile/dev.mk

###
### QA
### Commands to test the code quality
### ex: make test.all
### ¯¯¯¯¯¯¯¯¯¯¯

-include .makefile/qa.mk




