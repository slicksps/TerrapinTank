# TerrapinTank
Temperature checking, photo taking, relay operating Terrapin setup

Uses
Adafruit_Python_DHT with DHT11 Temperature hummidity sensor chip, 4 channel 5v relay switch.

Pinout for relay configurable in /home/pi/python/relay/states.json change names etc, groupings i.e. lights, all, lightsandheat are currently hard coded into /home/pi/python/relay/change.py

The relay allows for hardware default on or off. I was tempted to choose hardware on so that if the pi failed, I could have everything on without it in the loop, I opted instead for default off for safety, so that if anything was changed, I would be able to switch each output on one at a time.

I use Android app Raspberry SSH Custom Buttons by Knowlesonline for manual control and call the python script directly.

rc.local
python3 /home/pi/python/relay/restore.py

crontab
*/10 *    * * *  root    python3 /home/pi/python/tempsensor/temp.py >> /var/log/tempsensor.csv
*/15 *    * * *  root    /home/pi/takepic.sh
*/10 *    * * *  root    /home/pi/copylogstoserver.sh
0  8      * * *  pi      python3 /home/pi/python/relay/change.py lightsandheat on
30 00     * * *  pi      python3 /home/pi/python/relay/change.py Heat_Lamp off
0  1      * * *  pi      python3 /home/pi/python/relay/change.py lights off