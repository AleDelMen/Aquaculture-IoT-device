/*
 * HelTec Automation(TM) LoRaWAN 1.0.2 OTAA example use OTAA, CLASS A
 *
 * Function summary:
 *
 * - use internal RTC(150KHz);
 *
 * - Include stop mode and deep sleep mode;
 *
 * - Informations output via serial(115200);
 *
 * - Only ESP32 + LoRa series boards can use this library, need a license
 *   to make the code run(check you license here: http://www.heltec.cn/search );
 *
 * You can change some definition in "Commissioning.h" and "LoRaMac-definitions.h"
 *
 * HelTec AutoMation, Chengdu, China.
 * 成都惠利特自动化科技有限公司
 * https://heltec.org
 * support@heltec.cn
*/

//Imported libraries
#include <ESP32_LoRaWAN.h>  //This library is make LoRaWAN 1.0.2 protocol running with ESP32.
#include "Arduino.h"        //Main include file for the Arduino SDK
#include <EEPROM.h>         //brings in common print statements
#include <Ezo_i2c.h>        //include the EZO I2C library from https://github.com/Atlas-Scientific/Ezo_I2c_lib
#include <Wire.h>           //include arduinos i2c library
#include <Ezo_i2c_util.h>   //brings in common print statements

#define LORAWAN_DEFAULT_DATARATE                    DR_5
#define EEPROM_SIZE 12

//Multiplier parameter, to be able to use the lowbyte and highbyte functions
const int c = 1000;

//Pines used to enable/disable EZO's boards and TDS sensor.
int DO_trigger_int = 4;
gpio_num_t DO_trigger = (gpio_num_t)DO_trigger_int;

int PH_trigger_int = 2;
gpio_num_t PH_trigger = (gpio_num_t)PH_trigger_int;

int RTD_trigger_int = 15;
gpio_num_t RTD_trigger = (gpio_num_t)RTD_trigger_int;

int TDS_Isolator_int = 33;
int TDS_GND_Wire_int = 32;
gpio_num_t TDS_Isolator = (gpio_num_t)TDS_Isolator_int;
gpio_num_t TDS_GND_Wire = (gpio_num_t)TDS_GND_Wire_int;

//create a circuit object, who's address is XX and name is "BOAR_TYPE"
Ezo_board DO = Ezo_board(97, "DO");       
Ezo_board PH = Ezo_board(99, "PH");       
Ezo_board RTD = Ezo_board(102, "RTD");

//Parameters for the operation of the TDS sensor
#define TdsSensorPin 38 
#define VREF 3.3         // analog reference voltage(Volt) of the ADC
#define SCOUNT  30       // sum of sample point 
#define ADCRANGE 4095.0  

//Variables to store sensor data
float value_DO = 0.0;
float value_PH = 0.0;
float value_RTD = 0.0; 
float value_TDS = 0; 

//Variable for the minutes that pass between measurements and address where the variable is stored in the EEPROM
int minu;
int address = 0;
 
//License for Heltec ESP32 LoRaWan, quary your ChipID relevant license: http://resource.heltec.cn/search 
uint32_t  license[4] = {0xFEC7E5F8,0x1DC6E030,0xCAA96C89,0x692BB20A};

// OTAA parameters 
uint8_t DevEui[] = { 0x4d, 0xb8, 0x9c, 0xe3, 0x04, 0x25, 0x4e, 0x68 };
uint8_t AppEui[] = { 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00 };
uint8_t AppKey[] = { 0xf9, 0x4e, 0x3f, 0x87, 0xe4, 0xa6, 0x6d, 0xcb, 0x85, 0xe9, 0x1a, 0xba, 0x54, 0x22, 0x5c, 0x8b };

// ABP parameters 
//uint32_t DevAddr =  ( uint32_t )0x00425d48;
uint32_t DevAddr =  ( uint32_t )0x0033279e;

//uint8_t NwkSKey[] = { 0xb7, 0x75, 0x99, 0x0d, 0x1f, 0x99, 0xf7, 0xd4, 0x2b, 0x2b, 0x5c, 0x96, 0x77, 0x31, 0x09, 0x5d};
//uint8_t AppSKey[] = { 0x3e, 0x0b, 0x1a, 0x3f, 0x8e, 0xed, 0xc8, 0x59, 0x1d, 0x6c, 0xcd, 0x07, 0x5c, 0x81, 0xe6, 0x38};
                                                                                                                                                                                                                                                                                                                   
uint8_t NwkSKey[] = { 0x26, 0x60, 0x21, 0x5d, 0xd5, 0xd6, 0xb3, 0x26, 0x14, 0xa2, 0x3c, 0xd1, 0x64, 0x18, 0x3d, 0xbf};
uint8_t AppSKey[] = { 0x9d, 0xf7, 0x63, 0x38, 0x1a, 0x1b, 0xee, 0x85, 0xc8, 0x86, 0x15, 0xeb, 0x0f, 0xb8, 0xbc, 0x32};

/*LoraWan channelsmask*/
//It is indicated that only the first 3 channels will be used for communication
uint16_t userChannelsMask[6]={ 0x0007,0x0000,0x0000,0x0000,0x0000,0x0000 };

/*LoraWan Class, Class A and Class C are supported*/
DeviceClass_t  loraWanClass = CLASS_A;

/*the application data transmission duty cycle (value in [ms]).*/
uint32_t appTxDutyCycle;

/*OTAA or ABP*/
bool overTheAirActivation = true;

/*ADR enable*/
bool loraWanAdr = false;

/* Indicates if the node is sending confirmed or unconfirmed messages */
bool isTxConfirmed = true;

/* Application port */
uint8_t appPort = 2;

/*!
* Number of trials to transmit the frame, if the LoRaMAC layer did not
* receive an acknowledgment. The MAC performs a datarate adaptation,
* according to the LoRaWAN Specification V1.0.2, chapter 18.4, according
* to the following table:
*
* Transmission nb | Data Rate
* ----------------|-----------
* 1 (first)       | DR
* 2               | DR
* 3               | max(DR-1,0)
* 4               | max(DR-1,0)
* 5               | max(DR-2,0)
* 6               | max(DR-2,0)
* 7               | max(DR-3,0)
* 8               | max(DR-3,0)
*
* Note, that if NbTrials is set to 1 or 2, the MAC will not decrease
* the datarate, in case the LoRaMAC layer did not receive an acknowledgment
*/
uint8_t confirmedNbTrials = 3;

/*LoraWan debug level, select in arduino IDE tools.
* None : print basic info.
* Freq : print Tx and Rx freq, DR info.
* Freq && DIO : print Tx and Rx freq, DR, DIO0 interrupt and DIO1 interrupt info.
* Freq && DIO && PW: print Tx and Rx freq, DR, DIO0 interrupt, DIO1 interrupt and MCU deepsleep info.
*/
uint8_t debugLevel = LoRaWAN_DEBUG_LEVEL;

/*LoraWan region, select in arduino IDE tools*/
LoRaMacRegion_t loraWanRegion = ACTIVE_REGION;

//Function that calibrates the EZO boards as specified in the Atlas Scientific documentation
void calibrate_BOARD(Ezo_board BOARD, int sensor, int real_val, gpio_num_t trigger){
  gpio_hold_dis(trigger); 
  digitalWrite(trigger, LOW);  
  delay(2000);
  
  BOARD.send_cmd("Cal,clear");
  delay(600);

  int past_BOARDread;
  int current_BOARDread;
  int i = 0;

  BOARD.send_cmd("R");
  delay(1200);
  BOARD.send_read_cmd();
  delay(1200);
  receive_and_print_reading(BOARD);             //get the reading from the PH circuit
  past_BOARDread = BOARD.get_last_received_reading();
  delay(1000);

  while (i < 5){
    BOARD.send_cmd("R");
    delay(1200);
    BOARD.send_read_cmd();
    delay(1200);
    receive_and_print_reading(BOARD);             //get the reading from the PH circuit
    current_BOARDread = (int)BOARD.get_last_received_reading()*c;
    delay(1000);
    
    if(past_BOARDread == current_BOARDread){
      Serial.println("Las lecturas son las mismas, el valor de i es");
      i = i + 1;
      Serial.println(i);
    }
    else{
      Serial.println("Las lecturas noson las mismas, el valor de i es");
      i = 0;
      Serial.println(i);
      past_BOARDread = current_BOARDread;
    }
  }

  switch(sensor)
  {
    case 4:
    {
      if(real_val == 1){
        Serial.println("Se va a calibrar con empaque");
        BOARD.send_cmd("Cal,O");
        delay(2000);
      }
      if(real_val == 0){
        Serial.println("Se va a calibrar con el aire");
        BOARD.send_cmd("Cal");
        delay(2000);
      }
      break;
    }
    case 2:
    {
      if(real_val == 7){
        Serial.println("Se va a calibrar con empaque 7");
        BOARD.send_cmd("Cal,mid,7.00");
        delay(2000);
      }
      else if(real_val == 4){
        Serial.println("Se va a calibrar con empaque 4");
        BOARD.send_cmd("Cal,low,4.00");
        delay(2000);
      }
      if(real_val == 10){
        Serial.println("Se va a calibrar con empaque 10");
        BOARD.send_cmd("Cal,high,10.00");
        delay(2000);
      }
      break;
    }
    case 1:
    {
      Serial.println("Se va a calibrar RTD");
      float temperature = (float)real_val;
      String real_temp = String(temperature/1000);
      String command = "Cal,"+real_temp;
      char __command[sizeof(command)];
      command.toCharArray(__command, sizeof(__command));
      Serial.println("El comando seria");
      Serial.println(command);
      //const char *c = command.toCharArray();
      BOARD.send_cmd(__command);
      delay(2000);
      break;
    }
    default:
    {
      break;
    }
  }
  digitalWrite(trigger, HIGH);
  gpio_hold_en(trigger);
}

void action(uint8_t act, int arr[], int len){
  switch(act)
  {
    case 1:
    {
      if(len == 2){
        switch(arr[0])
        {
          case 4:
          {
            calibrate_BOARD(DO, 4, arr[1], DO_trigger);
            break;
          }
          case 2:
          {
            calibrate_BOARD(PH, 2, arr[1], PH_trigger);
            break;
          }
          case 1:
          {
            calibrate_BOARD(RTD, 1, arr[1], RTD_trigger);
            break;
          }
          default:
          {
            break;
          }
         }
      }
       break;
    }
    case 2:
    {
      if(len == 1){
        EEPROM.begin(EEPROM_SIZE);
        if(arr[0] > 1){
          EEPROM.put(address, arr[0]);
          EEPROM.commit();        
          EEPROM.get(address,minu);
          delay(10000);
        }
        else{
          EEPROM.put(address, 1);
          EEPROM.commit();
          EEPROM.get(address,minu);
          delay(10000);
        }
        EEPROM.end();
        appTxDutyCycle = minu * 60 * 1000;
      }
      break;
    }
    default:
    {
      break;
    }
   }
}

void  downLinkDataHandle(McpsIndication_t *mcpsIndication)
{
  lora_printf("+REV DATA:%s,RXSIZE %d,PORT %d\r\n",mcpsIndication->RxSlot?"RXWIN2":"RXWIN1",mcpsIndication->BufferSize,mcpsIndication->Port);

  uint8_t act = ((uint8_t)(mcpsIndication->Buffer[0]) << 8)
          + mcpsIndication->Buffer[1];
          
  int myVal = 0;
  int arr[((mcpsIndication->BufferSize) - 2)/2] = {};
  
  for(uint8_t i=1;i<=((mcpsIndication->BufferSize) - 2)/2;i = i + 1)
  {
    myVal = ((int)(mcpsIndication->Buffer[i*2]) << 8)
          + mcpsIndication->Buffer[i*2 + 1];
    arr[i - 1] = myVal;
  }
  int len = sizeof(arr) / sizeof(int);
  action(act, arr, len);
}

static void prepareTxFrame( uint8_t port )
{
    //Get RTD
    gpio_hold_dis(RTD_trigger); 
    digitalWrite(RTD_trigger, LOW);
    delay(2000);
    RTD.send_read_cmd();
    delay(815);
    //delay(1500);
    receive_and_print_reading(RTD);
    boolean success_temp = (RTD.get_error() == Ezo_board::SUCCESS) && (RTD.get_last_received_reading() > -1000.0);
    value_RTD = RTD.get_last_received_reading() * c;
    delay(815);
    digitalWrite(RTD_trigger, HIGH);
    gpio_hold_en(RTD_trigger); 
    
    //Get DO  
    gpio_hold_dis(DO_trigger); 
    digitalWrite(DO_trigger, LOW);  
    delay(2000);
    value_DO = get_value(DO, success_temp);
    digitalWrite(DO_trigger, HIGH);
    gpio_hold_en(DO_trigger); 

    //Get PH  
    gpio_hold_dis(PH_trigger); 
    digitalWrite(PH_trigger, LOW);  
    delay(2000);
    value_PH = get_value(PH, success_temp);
    digitalWrite(PH_trigger, HIGH);
    gpio_hold_en(PH_trigger); 

    //Get TDS
    gpio_hold_dis(TDS_Isolator);
    gpio_hold_dis(TDS_GND_Wire); 
    digitalWrite(TDS_Isolator,LOW); 
    digitalWrite(TDS_GND_Wire, HIGH);
    delay(1000);
    value_TDS = get_TDS() * c;
    digitalWrite(TDS_Isolator,HIGH); 
    digitalWrite(TDS_GND_Wire, LOW);
    gpio_hold_en(TDS_Isolator);
    gpio_hold_en(TDS_GND_Wire);

    appDataSize = 12;//AppDataSize max value is 64
    appData[0] = 1;
    appData[1] = highByte((int) value_RTD);
    appData[2] = lowByte((int) value_RTD);
    
    appData[3] = 4;
    appData[4] = highByte((int) value_DO);
    appData[5] = lowByte((int) value_DO);

    appData[6] = 2;
    appData[7] = highByte((int) value_PH);
    appData[8] = lowByte((int) value_PH);

    appData[9] = 3;
    appData[10] = highByte((int) value_TDS);
    appData[11] = lowByte((int) value_TDS);
}

float get_value(Ezo_board BOARD, boolean success_temp){
  if (success_temp) { //if the temperature reading has been received and it is valid
    BOARD.send_read_with_temp_comp(RTD.get_last_received_reading());                               //send readings from temp sensor to DO sensor
  }else {                                                                                      //if the temperature reading is invalid
    BOARD.send_read_with_temp_comp(25.0);                                                          //send default temp = 25 deg C to DO sensor
  }
  Serial.print(" ");
  delay(1000);
  receive_and_print_reading(BOARD);               //get the reading from the EC circuit and print it
  Serial.println();
  delay(2000);
  return BOARD.get_last_received_reading() * c;
}

float get_TDS(){
  int analogBuffer[SCOUNT];   // store the analog value in the array, read from ADC
  for (int i = 0; i < SCOUNT; i++)  //every 40 milliseconds,read the analog value from the ADC
  {
    analogBuffer[i] = analogRead(TdsSensorPin);    //read the analog value and store into the buffer
    delay(40U);
  }
  int analogBufferTemp[SCOUNT];
  for (int copyIndex = 0; copyIndex < SCOUNT; copyIndex++)
    analogBufferTemp[copyIndex] = analogBuffer[copyIndex];
  float averageVoltage = getMedianNum(analogBufferTemp, SCOUNT) * (float)VREF / ADCRANGE; // read the analog value more stable by the median filtering algorithm, and convert to voltage value      
  return averageVoltage;
}

int getMedianNum(int bArray[], int iFilterLen)
{
  int bTab[iFilterLen];
  for (byte i = 0; i < iFilterLen; i++)
    bTab[i] = bArray[i];
  int i, j, bTemp;
  for (j = 0; j < iFilterLen - 1; j++)
  {
    for (i = 0; i < iFilterLen - j - 1; i++)
    {
      if (bTab[i] > bTab[i + 1])
      {
        bTemp = bTab[i];
        bTab[i] = bTab[i + 1];
        bTab[i + 1] = bTemp;
      }
    }
  }
  if ((iFilterLen & 1) > 0)
    bTemp = bTab[(iFilterLen - 1) / 2];
  else
    bTemp = (bTab[iFilterLen / 2] + bTab[iFilterLen / 2 - 1]) / 2;
  return bTemp;
}

void setup()
{
  Wire.begin();                           //start the I2C
  Serial.begin(115200);
  while (!Serial);
  SPI.begin(SCK,MISO,MOSI,SS);
  Mcu.init(SS,RST_LoRa,DIO0,DIO1,license);
  deviceState = DEVICE_STATE_INIT;

  //Init EEPROM
  EEPROM.begin(EEPROM_SIZE);
  EEPROM.get(address,minu);
  EEPROM.end();
  
  appTxDutyCycle = minu * 60 * 1000;
  //appTxDutyCycle = 0.1 * 60 * 1000;

  pinMode(TdsSensorPin, INPUT);

  pinMode(DO_trigger, OUTPUT);
  pinMode(PH_trigger, OUTPUT);
  pinMode(RTD_trigger, OUTPUT); 
  pinMode(TDS_Isolator, OUTPUT); 
  pinMode(TDS_GND_Wire, OUTPUT);

  digitalWrite(RTD_trigger, HIGH);
  gpio_hold_en(RTD_trigger);
  digitalWrite(DO_trigger, HIGH);
  gpio_hold_en(DO_trigger);
  digitalWrite(PH_trigger, HIGH);
  gpio_hold_en(PH_trigger);
  digitalWrite(TDS_Isolator,HIGH); 
  digitalWrite(TDS_GND_Wire, LOW);
  gpio_hold_en(TDS_Isolator);
  gpio_hold_en(TDS_GND_Wire);
}

// The loop function is called in an endless loop
void loop()
{
  switch( deviceState )
  {
    case DEVICE_STATE_INIT:
    {
#if(LORAWAN_DEVEUI_AUTO)
      LoRaWAN.generateDeveuiByChipID();
#endif
      LoRaWAN.init(loraWanClass,loraWanRegion);
      break;
    }
    case DEVICE_STATE_JOIN:
    {
      LoRaWAN.join();
      break;
    }
    case DEVICE_STATE_SEND:
    {
      prepareTxFrame( appPort );
      LoRaWAN.send(loraWanClass);
      deviceState = DEVICE_STATE_CYCLE;
      break;
    }
    case DEVICE_STATE_CYCLE:
    {
      // Schedule next packet transmission
      txDutyCycleTime = appTxDutyCycle + randr( -APP_TX_DUTYCYCLE_RND, APP_TX_DUTYCYCLE_RND );
      LoRaWAN.cycle(txDutyCycleTime);
      deviceState = DEVICE_STATE_SLEEP;
      break;
    }
    case DEVICE_STATE_SLEEP:
    {
      LoRaWAN.sleep(loraWanClass,debugLevel);
      break;
    }
    default:
    {
      deviceState = DEVICE_STATE_INIT;
      break;
    }
  }
}
