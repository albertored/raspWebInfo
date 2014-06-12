#!/usr/bin/python -O

from datetime import datetime, timedelta
import sys
from time import sleep

# current time and minutes
now = datetime.now()

# current temperature
tfile = open("/sys/class/thermal/thermal_zone0/temp")
text = tfile.read()
tfile.close()
temperature = float(text) / 1000.0

# lengths data file
datafile = open("/path/to/info-rasp/temp_log.dat", "r")
lines = datafile.readlines()
datafile.close()

for iter in range(0,4):
	sleep(3*60) # sleep for 5 minutes
	tfile = open("/path/to/thermal/thermal_zone0/temp")
	text = tfile.read()
	tfile.close()
	temperature = temperature + float(text) / 1000.0
now_plus_15 = now + timedelta(minutes = 15)
timestamp = now_plus_15.strftime("%d-%m-%Y %H:%M:%S")
sleep(2*60+55)
if len(lines)>199: # 2 days * 24 hours * 4 times/hour = 192 times 
	datafile = open("/path/to/info-rasp/temp_log.dat", "w")
	datafile.writelines(lines[1:])
	datafile.write(timestamp + " " + str(temperature/5.0) + "\n")
else:
	datafile = open("/path/to/info-rasp/temp_log.dat", "a")
        datafile.write(timestamp + " " + str(temperature/5.0) + "\n")
datafile.close()

