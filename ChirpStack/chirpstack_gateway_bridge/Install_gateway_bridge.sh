#Upgrade the Raspberry Pi packages.
sudo apt-get update && sudo apt-get upgrade -y

#Install and run the MQTT Mosquitto
sudo apt-get install mosquitto -y
sudo apt-get install mosquitto-clients -y
sudo systemctl enable mosquitto

#The instructions for Raspberry Pi OS are simplified, 
#the first ones are to access the installation repository 
sudo apt install apt-transport-https dirmngr -y
sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 1CE2AFD36DBCCA00
sudo echo "deb https://artifacts.chirpstack.io/packages/3.x/deb stable main" | sudo tee /etc/apt/sources.list.d/chirpstack.list
sudo apt-get update

#Install the component.
sudo apt install chirpstack-gateway-bridge

#Before continuing, the chirpstack-gateway-bridge.toml file must be configured.

#After configuring the .toml file, start the component
sudo systemctl start chirpstack-gateway-bridge

#Verify  if the Gateway Bridge is running and check if there are not errors.
sudo systemctl status chirpstack-gateway-bridge

#Start chirpstack-gateway-bridge on boot.
sudo systemctl enable chirpstack-gateway-bridge