import json
import sys
import RPi.GPIO as GPIO
import os
import time
GPIO.setwarnings(False)
filename='/home/pi/python/relay/states.json'
if filename:
    with open(filename, 'r') as f:
        datastore = json.load(f)
try:
    device=sys.argv[1]
except:
    device='none'
if device in datastore:
    devPin=datastore[device]['pin']
elif device != 'all' and device != 'lights' and device != 'lightsandheat':
    print("Missing Device, valid devices:")
    for key in datastore:
        print(key+" "+str(datastore[key]['state']))
    exit()
try:
    state=sys.argv[2]
except:
    state=11
if state == 'on':
    state = 1
if state == 'off':
    state = 0
if(int(state) != 1 and int(state) != 0):
    print('Must be on or off, 1 or 0')
    exit()
GPIO.setmode(GPIO.BCM)
if(device == 'all' or device == 'lights' or device == 'lightsandheat'):
    for key in datastore:
        devPin=datastore[key]['pin']
        if(device == 'all' or (device == 'lights' and (key == 'Upper_Light' or key == 'Lower_Light')) or (device == 'lightsandheat' and (key == 'Upper_Light' or key == 'Lower_Light' or key == 'Heat_Lamp'))):
            GPIO.setup(devPin, GPIO.OUT)
            if int(state) == 1:
                GPIO.output(devPin,GPIO.LOW)
                print("Switching "+key+" on pin "+str(devPin)+" on")
            else:
                GPIO.output(devPin,GPIO.HIGH)
                print("Switching "+key+" pin "+str(devPin)+" off")
            datastore[key]['state'] = state
            time.sleep(0.5)
else:
    GPIO.setup(devPin, GPIO.OUT)
    if int(state) == 1:
        GPIO.output(devPin,GPIO.LOW)
        print("Switching "+device+" on pin "+str(devPin)+" on")
    else:
         GPIO.output(devPin,GPIO.HIGH)
         print("Switching "+device+" pin "+str(devPin)+" off")
    datastore[device]['state'] = state
with open(filename, 'w') as outfile:
    json.dump(datastore, outfile)
#GPIO.cleanup()
os.system('sudo /home/pi/copylogstoserver.sh')
exit()
