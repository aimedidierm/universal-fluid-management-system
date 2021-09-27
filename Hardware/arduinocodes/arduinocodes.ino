/*
Pump -> 4
Stopbutton -> 5
Startbutton -> 6
trigger -> 7
echo -> 8
SDA -> A4
SCL-> A5
*/
#include <SoftwareSerial.h>
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#define trigger 7
#define echo 8
#define rxPin 10
#define txPin 11

SoftwareSerial sim800l(10, 11); // RX,TX for Arduino and for the module it's TXD RXD, they should be inverted 

const int Startbutton =  6;
const int Stopbutton =  5;
const int pump =  4;
int var=0;
/*
22cm -> 100% max
tanksize = 22/100
litres = 5
*/
float tanksize=0.22,time=0,distance=0,precent=0, liters=5,liveliters=0;

int StartbuttonState = 0;
int StopbuttonState = 0;
int pumpState = 0;

LiquidCrystal_I2C lcd(0x27,20,4);  // set the LCD address to 0x27 for a 16 chars and 2 line display

void setup()
{
  sim800l.begin(9600);
  pinMode(trigger,OUTPUT);
  pinMode(echo,INPUT);
  pinMode(Startbutton,INPUT);
  pinMode(Stopbutton,INPUT);
  pinMode(pump,OUTPUT);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0,0);
  lcd.print("Universal Fluid");
  lcd.setCursor(4,1);
  lcd.print("M system");
  lcd.setCursor(0,2);
  delay(2000);
}


void loop()
{
 StartbuttonState = digitalRead(Startbutton);
 StopbuttonState = digitalRead(Stopbutton);
 lcd.clear();
 digitalWrite(trigger,LOW);
 delayMicroseconds(2);
 digitalWrite(trigger,HIGH);
 delayMicroseconds(10);
 digitalWrite(trigger,LOW);
 delayMicroseconds(2);
 time=pulseIn(echo,HIGH);
 distance=time*340/20000;
 precent=100-(distance/tanksize);
 liveliters=(precent/100)*liters;
 String url;
 url = "http://147.182.250.105/universal-fluid-management-system/gsmdata.php?per=";
 url += precent;
 
 sim800l.print("AT+HTTPPARA=\"URL\",\"");
 sim800l.print(url);
 lcd.clear();
 lcd.setCursor(0,0);
 lcd.print(liveliters);
 lcd.print(" L");
 lcd.setCursor(0,1);
 if (pumpState == 0) {     
    lcd.print("Pump OFF");
  } else {
    lcd.print("Pump ON");
  }
 
if (StartbuttonState == HIGH) {   
    // turn Pump on:    
    digitalWrite(pump, HIGH);
    pumpState = 1; 
  } 
if (StopbuttonState == HIGH) {  
    // turn Pump off:    
    digitalWrite(pump, LOW);
    pumpState = 0;
  }
  
  if (liveliters >0 && liveliters <=4) {   
    // turn Pump off:
    var=0;
  } else {
    digitalWrite(pump, LOW);
    pumpState = 0;
  }
  if (liveliters <=0.6) {   
    SendSMS();
  } else {
    }
 delay(1000);
}
void SendSMS()
{
  Serial.println("Sending SMS...");               //Show this message on serial monitor
  sim800l.print("AT+CMGF=1\r");                   //Set the module to SMS mode
  delay(100);
  sim800l.print("AT+CMGS=\"+250788750979\"\r");  //Your phone number don't forget to include your country code, example +212123456789"
  delay(500);
  sim800l.print("Balance is low 15%");       //This is the text to send to the phone number, don't make it too long or you have to modify the SoftwareSerial buffer
  delay(500);
  sim800l.print((char)26);// (required according to the datasheet)
  delay(500);
  sim800l.println();
  Serial.println("Text Sent.");
  delay(500);
  var=1;
  /*
  Serial.println("Sending SMS...");               //Show this message on serial monitor
  sim800l.print("AT+CMGF=1\r");                   //Set the module to SMS mode
  delay(100);
  sim800l.print("AT+CMGS=\"+250781196048\"\r");  //Your phone number don't forget to include your country code, example +212123456789"
  delay(500);
  sim800l.print("Balance is low 15%");       //This is the text to send to the phone number, don't make it too long or you have to modify the SoftwareSerial buffer
  delay(500);
  sim800l.print((char)26);// (required according to the datasheet)
  delay(500);
  sim800l.println();
  Serial.println("Text Sent.");
  delay(500); 
  Serial.println("Sending 2nd SMS...");               //Show this message on serial monitor
  sim800l.print("AT+CMGF=1\r");                   //Set the module to SMS mode
  delay(100);
  sim800l.print("AT+CMGS=\"+250788856865\"\r");  //Your phone number don't forget to include your country code, example +212123456789"
  delay(500);
  sim800l.print("Balance is low 15%");       //This is the text to send to the phone number, don't make it too long or you have to modify the SoftwareSerial buffer
  delay(500);
  sim800l.print((char)26);// (required according to the datasheet)
  delay(500);
  sim800l.println();
  Serial.println("Text Sent.");
  delay(500);
   */
}
