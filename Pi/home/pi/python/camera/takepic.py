from picamera import PiCamera
import time
import datetime
camera = PiCamera()
camera.rotation = 180
camera.start_preview()
time.sleep(5)
ts = time.time()
filename = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
camera.capture('/home/pi/python/camera/images/'+filename+'.jpg')
camera.stop_preview()
