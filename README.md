# raspWebInfo

**raspWebInfo** is a web interface for your *Raspberry Pi* showing a lot of useful informations of your device.

It is composed by a principal `php` page that collects all infos and by some `python` and `bash` scripts that help getting and formatting the data.

Features supported are:

* temperature graph (using [highcharts](http://www.highcharts.com/) script)
* uptime
* available updates
* CPU loads
* memory used (progress bar from [Thibaut Courouble](http://www.cssflow.com/snippets/animated-progress-bar))
* disk space (progress bar from [Thibaut Courouble](http://www.cssflow.com/snippets/animated-progress-bar))

## Requirements

Requirements for the correct functionality of this tools are:

* *Raspbian*: this web interface has been tested only on Raspbian systems, on different ones some features may not work.
* *apt-check*: you can install it with `sudo apt-get install update-notifier-common`
   
## Getting Started


## Examples

This is a screenshot of the interface:

![screenshot](https://raw.githubusercontent.com/albertored/raspWebInfo/master/screenshot.png)

