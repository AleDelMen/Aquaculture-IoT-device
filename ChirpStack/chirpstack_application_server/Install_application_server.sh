#The Application Server needs its own database.
#So here are the steps to create a new database.
#With the following instruction the PostgreSQL prompt will be accessed.
sudo -u postgres psql
#Within the PostgreSQL prompt, a role is created and a password is assigned
create role cs_as with login password 'password';
#Then the database is created with the previously created role as owner
create database cs_as with owner cs_as;
#Enable the pg_trgm (trigram) and hstore extensions.
\c cs_as
create extension pg_trgm;
create extension hstore;
\q
#It is verified that the database and the user have been correctly created
psql -h localhost -U cs_as -W cs_as
\q

#Install the component.
sudo apt-get install chirpstack-application-server

#You have to create JSON Web Token (jwt). So, you have to type the folling command 
#in the prompt
openssl rand -base64 32
#This command returns a character string as output, it is important to keep this string secret.
#The character string will be used in the configuration of chirpstack-application-server.toml file.
#Before continuing, the chirpstack-application-server.toml file must be configured.

#After configuring the .toml file, start the component
sudo systemctl start chirpstack-application-server

#Verify if the Application Server is running and check if there are not errors.
systemctl status chirpstack-application-server

#Start chirpstack-application-server on boot.
sudo systemctl enable chirpstack-application-server