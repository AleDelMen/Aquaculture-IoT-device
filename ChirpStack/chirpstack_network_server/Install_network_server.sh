#Install PostgreSQL
sudo apt-get install postgresql -y

#Install Redis server and Redis tools.
sudo apt-get install redis-server -y
sudo apt-get install redis-tools -y

#The Network Server needs its own database.
#So here are the steps to create a new database.
#With the following instruction the PostgreSQL prompt will be accessed
sudo -u postgres psql
#Within the PostgreSQL prompt, a role is created and a password is assigned
create role cs_ns with login password 'password';
#Then the database is created with the previously created role as owner
create database cs_ns with owner cs_ns;
#Enable the pg_trgm (trigram) and hstore extensions.
\c cs_ns
create extension pg_trgm;
create extension hstore;
\q
#It is verified that the database and the user have been correctly created
psql -h localhost -U cs_ns -W cs_ns
\q

#Install the component.
sudo apt-get install chirpstack-network-server -y

#Before continuing, the chirpstack-network-server.toml file must be configured.

#After configuring the .toml file, start the component
sudo systemctl start chirpstack-network-server

#Verify if the Network Server is running and check if there are not errors.
systemctl status chirpstack-network-server

#Start chirpstack-network-server on boot.
sudo systemctl enable chirpstack-network-server