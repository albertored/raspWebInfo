#!/usr/bin/python -O

import sys
from time import time, sleep

# current temperature
tfile = open("/sys/class/thermal/thermal_zone0/temp")
text = tfile.read()
tfile.close()
temperature = float(text) / 1000.0

# lengths data file
datafile = open("/path/to/info-rasp/temp_long_log.dat", "r")
lines = datafile.readlines()
datafile.close()

datafile = open("/path/to/info-rasp/startdate.dat", "w")
currentmins = int( time() / 60 )
datafile.write( str( (currentmins-len(lines)*5)*60 + 5*60 ) )
datafile.close()

for iter in range(0,4):
        sleep(60) # sleep for 1 minutes
        tfile = open("/sys/class/thermal/thermal_zone0/temp")
        text = tfile.read()
        tfile.close()
        temperature = temperature + float(text) / 1000.0
sleep(55)
if len(lines)>8639: # 30 days * 24 hours * 12 times/hour = 8640 times 
        datafile = open("/path/to/info-rasp/temp_long_log.dat", "w")
        datafile.writelines(lines[1:])
        datafile.write(str(temperature/5.0) + "\n")
else:
        datafile = open("/path/to/info-rasp/temp_long_log.dat", "a")
        datafile.write(str(temperature/5.0) + "\n")
datafile.close()

