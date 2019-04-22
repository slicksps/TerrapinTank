import json
import sys
import RPi.GPIO as GPIO
import os
import time
GPIO.setwarnings(False)
filename='/home/pi/python/relay/states.json'
if filename:
    GPIO.setmode(GPIO.BCM)
    with open(filename, 'r') as f:
        datastore = json.load(f)
    for key in datastore:
         GPIO.setup(int(datastore[key]['pin']), GPIO.OUT)
         if int(datastore[key]['state']) == 1:
             GPIO.output(int(datastore[key]['pin']),GPIO.LOW)
             print("Switching "+key+" on pin "+str(datastore[key]['pin'])+" on")
         else:
             GPIO.output(int(datastore[key]['pin']),GPIO.HIGH)
             print("Switching "+key+" pin "+str(datastore[key]['pin'])+" off")
         time.sleep(0.5)
