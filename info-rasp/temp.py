#!/usr/bin/python -O

from datetime import datetime
import sys
import time

# current time
now = datetime.now()
timestamp = now.strftime("%d-%m-%Y %H:%M:%S")
minutes = now.minute

# current temperature
tfile = open("/sys/class/thermal/thermal_zone0/temp")
text = tfile.read()
tfile.close()
temperature = float(text) / 1000.0

# lengths data files
datafile = open("/var/www/info-rasp/temp_log.dat", "r")
lines = datafile.readlines()
datafile = open("/var/www/info-rasp/temp_long_log.dat", "r")
lineslong = datafile.readlines()

datafile = open("/var/www/info-rasp/startdate.dat", "w")
currentmins = int(time.time() / 60)
datafile.write( str( (currentmins-len(lineslong))*60 ) )

if ( minutes % 15) == 0:
	if len(lines)>199: # 2 giorni * 24 ore * 4 volte all'ora = 192 volte 
		datafile = open("/var/www/info-rasp/temp_log.dat", "w")
		datafile.writelines(lines[1:])
		datafile.write(timestamp + " " + str(temperature) + "\n")
	else:
		datafile = open("/var/www/info-rasp/temp_log.dat", "a")
	        datafile.write(timestamp + " " + str(temperature) + "\n")
	datafile.close()

if len(lineslong)>43199: # 30 giorni * 24 ore * 60 volte all'ora = 43200 volte 
	datafile = open("/var/www/info-rasp/temp_long_log.dat", "w")
	datafile.writelines(lineslong[1:])
        datafile.write(str(temperature) + "\n")
else:
       	datafile = open("/var/www/info-rasp/temp_long_log.dat", "a")
        datafile.write(str(temperature) + "\n")
datafile.close()

