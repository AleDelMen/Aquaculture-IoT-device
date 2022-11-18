#Libraries for obtaining data through MQTT
import paho.mqtt.client as mqtt
import json

#Database library
import mysql.connector as mariadb

#Libraries for sending mail
import smtplib 
from email.message import EmailMessage

#Library for push notifications
from pushbullet import Pushbullet

#Library for time control
import time

#Global variables to count the attempts of some action
MQTT_attempts = 0
attempts_to_enter_data = 0
attempts_to_connect_DB = 0
attempts_to_send_mail = 0

def push_notification(title, message):
    API_KEY = 'YOUR API KEY'
    pb = Pushbullet(API_KEY)
    chats = pb.contacts
    for i in chats:
        i.push_note(title, message)
    
def insert_alert(tipo, message):
    push_notification(tipo, message)
    
    cn = connection_db()
    cursor = cn.cursor()

    sql_statement = 'INSERT INTO Alertas (Tipo, Mensaje) VALUES (%s, %s)';
    items_to_insert = (tipo, message)
    cursor.execute(sql_statement, items_to_insert)
    cn.commit()

    try:
        cursor.close()
        cn.close()
    except Exception as e:
        push_notification('Error', 'No se pudo cerrar la conexión con la base de datos')

def send_email(m, address):
    global attempts_to_send_mail
    try:
        message = EmailMessage()
        email_subject = "Valor fuera del rango aceptable de una variable "
        sender_email_address = "YOUR GMAIL" 
        email_password = 'GMAIL PASSWORD'
        receiver_email_address = address

        # Configure email headers 
        message['Subject'] = email_subject 
        message['From'] = sender_email_address 
        message['To'] = receiver_email_address

        #Set email body text
        message.set_content(m)

        # Set smtp server and port
        email_smtp = "smtp.gmail.com"  
        server = smtplib.SMTP(email_smtp, '587')

        # Identify this client to the SMTP server 
        server.ehlo() 

        # Secure the SMTP connection 
        server.starttls()

        # Login to email account 
        server.login(sender_email_address, email_password) 

        # Send email 
        server.send_message(message) 

        # Close connection to server 
        server.quit()

        attempts_to_send_mail = 0
    except Exception as e:
        time.sleep(5)
        attempts_to_send_mail += 1
        if attempts_to_send_mail >= 5:
            insert_alert('Error', 'No se pudo mandar correo')
        else:
            send_email(m, address)

def connection_db():
    global attempts_to_connect_DB

    config = {
        'user' : 'YOUR DB USER',
        'password' : 'DB PASWORD',
        'host' : 'localhost',
        'port' : '3306',
        'database' : 'NAME OF DB'
    }
    try:
        c = mariadb.connect(**config)
        attempts_to_connect_DB = 0
        return c
    except:
        time.sleep(5)
        attempts_to_connect_DB += 1
        if attempts_to_connect_DB >= 5:
            push_notification('Error','No se pudo conectar con la base de datos.')
        else:
            connection_db()

def get_boya(cursor, Dev_EUI):
    sql_statement = 'SELECT ID FROM Catalogo_boyas WHERE Dev_EUI = \'%s\' ' % Dev_EUI
    cursor.execute(sql_statement)
    ID_boya = cursor.fetchone() #ID[0]
    return ID_boya

def get_data_DB(cursor, dato, tabla, where, items_to_insert):
    sql_statement = 'SELECT '+dato+' FROM '+tabla+' WHERE '+where;
    cursor.execute(sql_statement, items_to_insert)
    ID_data = cursor.fetchone() #ID[0]
    return ID_data

def update_data_DB(cursor, cn, dato, tabla, where, items_to_insert):
    sql_statement = 'UPDATE '+tabla+' SET '+dato+' WHERE '+where;
    cursor.execute(sql_statement, items_to_insert)
    cn.commit()

def insert_data(data, cursor, cn):
    global attempts_to_enter_data
    try:
        ID_sensor = get_data_DB(cursor, 'ID', 'Catalogo_sensores','Boya = %s AND Variable = %s', (data[0], data[1]))
        sql_statement = 'INSERT INTO Mediciones (Sensor, Valor, Voltaje) VALUES (%s, %s, %s)';
        items_to_insert = (ID_sensor[0], data[2], data[3])
        cursor.execute(sql_statement, items_to_insert)
        cn.commit()
        attempts_to_enter_data = 0

    except Exception as e:
        time.sleep(5)
        attempts_to_enter_data += 1
        if attempts_to_enter_data >= 5:
            insert_alert('Error', 'No se pueden ingresar datos a la base')
        else:
            insert_data(data, cursor, cn)
    

def get_TDS(temperature, averageVoltage):
    compensationCoefficient=1.0+0.02*(temperature-25.0) #temperature compensation formula: fFinalResult(25^C) = fFinalResult(current)/(1.0+0.02*(fTP-25.0));
    compensationVolatge=averageVoltage/compensationCoefficient #temperature compensation
    tdsValue=(133.42*compensationVolatge*compensationVolatge*compensationVolatge - 255.86*compensationVolatge*compensationVolatge + 857.39*compensationVolatge)*0.5 #convert voltage value to tds value
    return round(tdsValue,4)

def get_emails(cursor):
    cn = connection_db()
    cursor = cn.cursor()
    sql_statement = 'SELECT Correo FROM Correos WHERE Avisos = 1 AND Activo = 1';
    cursor.execute(sql_statement)
    return cursor.fetchall()


# Receiving CONNACK from the server.

def on_connect(client, userdata, flags, rc): #Function that is executed when it connects to the server successfully
    print("Conexión/código de resultado: "+str(rc))

    cn = connection_db()
    cursor = cn.cursor()
    sql_statement = 'SELECT Dev_EUI, Aplication FROM Catalogo_boyas'
    cursor.execute(sql_statement)
    result = cursor.fetchall()

    for i in result:
        topicolee = 'application/'+str(i[1])+'/device/'+i[0]+'/event/up'
        # Subscription start or renewal.
        client.subscribe(topicolee, qos=2)
    
    cursor.close()
    cn.close()
    return()

#The topic has a publication
def on_message(client, userdata, msg): #Function that is executed when receiving a message
    #Open connection with DB
    cn = connection_db()
    cursor = cn.cursor()

    #The message is received by MQTT
    msg_rx = str(msg.payload.decode('utf-8'))
    data = json.loads(msg_rx)
    print(data)

    #The data of interest is obtained
    Dev_EUI = data['devEUI']
    ID_boya = get_boya(cursor, Dev_EUI)

    if '3' in data['object'].keys():
        if '1' in data['object'].keys():
            temperatura = data['object']['1']
        else:
            temperatura = 25

    for sensor in data['object'].keys():
        if sensor == '3':
            sensor_value = get_TDS(temperatura, data['object'][sensor])
        else:
            sensor_value = data['object'][sensor]
            data['object'][sensor] = None

        #Data inserted into the DB
        frame = [ID_boya[0], sensor, sensor_value, data['object'][sensor]]
        print(frame)

        try:
            insert_data(frame, cursor, cn)
            
            #Verify that the data is within the specified range 
            min = get_data_DB(cursor, 'Valor_min', 'Catalogo_sensores', 'Boya = %s AND Variable = %s', (ID_boya[0], sensor))
            max = get_data_DB(cursor, 'Valor_max', 'Catalogo_sensores', 'Boya = %s AND Variable = %s', (ID_boya[0], sensor))
            
            if min[0] >= sensor_value  or sensor_value  >= max[0]:
                alarma = get_data_DB(cursor, 'Alerta', 'Catalogo_sensores', 'Boya = %s AND Variable = %s', (ID_boya[0], sensor))
                if alarma[0] == 0:
                    #If the data is out of range, an email is sent for the first time
                    
                    unidades = get_data_DB(cursor, 'Unidades', 'Catalogo_sensores','Boya = %s AND Variable = %s', (ID_boya[0], sensor))
                    sql_statement = 'SELECT Tipo FROM Catalogo_tipo_sensores WHERE ID = %s' % sensor
                    cursor.execute(sql_statement)
                    var_sensor = cursor.fetchone()

                    message = 'El valor de la variable '+var_sensor[0]+' es de '+ str(sensor_value)+unidades[0]
                    push_notification('Variable fuera de rango', message)
                    
                    correos = get_emails(cursor)
                    for i in correos:
                        send_email(message,i[0])
                        time.sleep(5)
                    update_data_DB(cursor, cn, 'Alerta = 1', 'Catalogo_sensores', 'Boya = %s AND Variable = %s', (ID_boya[0], sensor))
            else:
                update_data_DB(cursor, cn, 'Alerta = 0', 'Catalogo_sensores', 'Boya = %s AND Variable = %s',(ID_boya[0], sensor))
        
        except Exception as e:
            insert_alert('Error', 'No se pudo realizar el procesado de los datos')     
    try:
        cursor.close()
        cn.close()
    except Exception as e:
        insert_alert('Error','No se pudo cerrar la conexión con la base de datos')
    return()


def main():
    global MQTT_attempts
    servidormqtt = "localhost"

    client = mqtt.Client() #An MQTT client instance is declared
    client.on_connect = on_connect #Function that is executed when the connection is made
    client.on_message = on_message #Function that is executed when a message arrives
    try:
        client.connect(servidormqtt, 1883, 60) #Conexion ip, port, timeAlive
        client.loop_forever()
        MQTT_attempts = 0
    except Exception as e:
        time.sleep(5)
        MQTT_attempts += 1
        if MQTT_attempts >= 5:
            insert_alert('Error', 'No se pudo conectar con el broker MQTT')
        else:
            main()

if __name__ == '__main__':
    main()
