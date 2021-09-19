#include <SoftwareSerial.h>

#define rxPin 10
#define txPin 11
SoftwareSerial sim800L(rxPin,txPin); 

void setup()
{
  //Begin serial communication with Arduino and Arduino IDE (Serial Monitor)
  Serial.begin(115200);
  
  //Begin serial communication with Arduino and SIM800L
  sim800L.begin(115200);

  Serial.println("Initializing...");
  delay(1000);
}

void loop()
{
  while(sim800L.available()){
    Serial.println(sim800L.readString());
  }
  while(Serial.available())  {
    sim800L.println(Serial.readString());
  }
  init_sms();
  send_data("Hi biraza kujyenda neza");
  send_sms();
  delay(5000);
}
void init_sms()
{
   sim800L.println("AT+CMGF=1");
   delay(200);
   sim800L.println("AT+CMGS=\"+250788750979\"");
   delay(200);
}
void send_data(String message)
{
  sim800L.println(message);
  delay(200);
}
void send_sms()
{
  sim800L.write((char)26);
}
