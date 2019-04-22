cd /home/pi/
./systemstate.sh
scp /var/log/tempsensor.csv [REMOTE SERVER]
scp /home/pi/systemstate.txt [REMOTE SERVER]
scp /home/pi/python/relay/states.json [REMOTE SERVER]
scp /home/pi/topprocesses.txt [REMOTE SERVER]