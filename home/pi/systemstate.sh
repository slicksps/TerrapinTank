echo -n "Update: " > systemstate.txt
date | cut -c1-16 >> systemstate.txt
echo -n "Uptime: " >> systemstate.txt
uptime -p | cut -c4-50 >> systemstate.txt
echo -n "Since: " >> systemstate.txt
uptime -s >> systemstate.txt
echo -n "CPU: " >> systemstate.txt
echo -n $((`cat /sys/class/thermal/thermal_zone0/temp|cut -c1-2`)).$((`cat /sys/class/thermal/thermal_zone0/temp|cut -c2-3`)) >> systemstate.txt
echo "C" >> systemstate.txt
echo -n "Used Disk: " >> systemstate.txt
df -h --output=used / | tail -n 1 | cut -c2-6 >> systemstate.txt
echo -n "Free Space: " >> systemstate.txt
df -h --output=avail / | tail -n 1 | cut -c3-6 >> systemstate.txt
cat systemstate.txt
sudo top -b -n 1 > /home/pi/topprocesses.txt
