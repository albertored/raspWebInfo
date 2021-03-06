# raspWebInfo

**raspWebInfo** is a web interface for your *Raspberry Pi* showing a lot of useful informations of your device.

It is composed by a principal `php` page that collects all infos and by some `python` and `bash` scripts that help getting and formatting the data.

Features supported are:

* temperature graph (using [highcharts](http://www.highcharts.com/) script)
* uptime
* available updates
* CPU loads
* memory used (*)
* disk space (*)

(*) progress bar from [Thibaut Courouble](http://www.cssflow.com/snippets/animated-progress-bar) with percentage added by me

## Requirements

Requirements for the correct functionality of this tools are:

* *Raspbian*: this web interface has been tested only on Raspbian systems, on different ones some features may not work.
* *apt-check*: you can install it with `sudo apt-get install update-notifier-common`
   
## Getting Started

In the package are already present some data files. In order to update these files with values from your raspberry you have to:

1. delete these files: `rm info-rasp/startdate.dat info-rasp/temp_log.dat info-rasp/temp_long_log.dat info-rasp/updates/updates.txt`
2. recreating an empty version of them: `touch info-rasp/startdate.dat info-rasp/temp_log.dat info-rasp/temp_long_log.dat info-rasp/updates/updates.txt`
3. make the scripts executable: `chmod +x info-rasp/temp.py info-rasp/updates/updates.sh`
4. substitute the `/path/to/` strings in the `index.php`, `info-rasp/temp*.py` files with the correct path in which you put the codes
5. adding these scripts to your *crontab* with `sudo crontab -e` and writing at the bottom

```
# get the temperature every minute in the detailed graph
# and every 15 minutes in the main one
*/1 * * * * /path/to/script/./temp.py > /dev/null

# if you want the mean temperature over 15 minutes for the main graph
# and over 5 minutes in the detailed one add instead
*/15 * * * * /path/to/script/./temp15.py > /dev/null
*/5 * * * * /path/to/script/./temp5.py > /dev/null


# check the presence of updates at 8:00 AM and 4:00 PM every day
0 8,16 * * * /path/to/script/./updates.sh > /dev/null
```

## Examples

This is a screenshot of the interface:

![screenshot](https://raw.githubusercontent.com/albertored/raspWebInfo/master/screenshot.png)

