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

SoftwareSerial sim800L(rxPin,txPin); 

const int Startbutton =  6;
const int Stopbutton =  5;
const int pump =  4;
/*
22cm -> 100%
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
  sim800L.begin(115200);
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
 url = "http://ENTER_YOUR_WEBSITE/gsmdata.php?per=";
 url += precent;
 sim800L.print("AT+HTTPPARA=\"URL\",\"");
 sim800L.print(url);
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
  
  if (precent >0 && precent <=85) {   
    // turn Pump off:
  } else {
    digitalWrite(pump, LOW);
    pumpState = 0;
  }
  if (precent <=15) {   
    sim800L.println("ATD +250788750979;");
    
  }
 delay(1000);
}
