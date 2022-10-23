mkdir lora
cd lora
sudo apt-get update
sudo apt-get install git
git clone https://github.com/Lora-net/picoGW_hal.git
# LoRa Gateway drivers
git clone https://github.com/Lora-net/picoGW_packet_forwarder.git
# packet forwarding software
git clone https://github.com/HelTecAutomation/picolorasdk.git
# This package will create a "lrgateway" service in Raspberry Pi
cd /home/pi/lora/picoGW_hal
make clean all
cd /home/pi/lora/picoGW_packet_forwarder
make clean all
cd /home/pi/lora/picolorasdk
chmod +x install.sh
./install.sh    #Run the script. After the script is run, it will create a service
                #named "lrgateway". The purpose is to make the lora driver and data forwarding
                #program run automatically at startup.
sudo cp -f /home/pi/lora/picolorasdk/global_conf_EU433.json /home/pi/lora/picoGW_packet_forwarder/lora_pkt_fwd/global_conf.json 
#Put the configuration file on the specified path
