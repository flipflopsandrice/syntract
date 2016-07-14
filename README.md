# Syntract

## Introduction

Syntract is a tool that sync a remote filesystem with your local filesystem and automatically extracts any (supported) archives it can find.

## Running

### Pre-requisites

Makefile, PHP, composer installed globally, rar/zip, RSync

## Installation

### Dependencies

Run `make install`

### Configuration

Copy `config/config-example.yml` to `config/config.yml` and edit the required parameters.

## Running

### From CLI

To run the entire sync & extract process use: `make all`.

It's also possible isolate a task, perhaps when you wish to schedule them separately: `make sync|extract` 

### Scheduling

Add the following to crontab to execute it every 5 minutes.

``` 
*/5 * * * * $(which make) -C /path/to/syntract all
```

## Todo

* Pre- and Post-command hooks
* Pluggable extensible tasks

