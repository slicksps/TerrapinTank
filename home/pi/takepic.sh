python3 /home/pi/python/camera/takepic.py
ls -lt /home/pi/python/camera/images/*.jpg | head -5 | awk '{print "scp \"" $9 " " $10 "\" [REMOTE SERVER]"}' | sh
scp /var/log/tempsensor.csv [REMOTE SERVER]
scp /home/pi/systemstate.txt [REMOTE SERVER]
