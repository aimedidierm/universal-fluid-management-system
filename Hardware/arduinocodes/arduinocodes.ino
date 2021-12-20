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

const int Startbutton =  6;
const int Stopbutton =  5;
const int pump =  4;
int var=0;
String apn = "www.tigo.rw";                    //APN
String apn_u = "";                     //APN-Username
String apn_p = "";                     //APN-Password
String url = "http://143.110.228.128/universal-fluid-management-system/gsmdata.php?per=";  //URL of Server
SoftwareSerial SWserial(10, 11); // RX, TX
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
  Serial.begin(115200);
  Serial.println("SIM800 AT CMD Test");
  SWserial.begin(9600);
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
  while (SWserial.available()) {
    Serial.write(SWserial.read());
  }
  delay(500);
  gsm_config_gprs();
}

void loop() {
  control();
  SWserial.println();
  SWserial.println("AT+SAPBR=1,1");
  delay(3000);
  control();
  SWserial.println("AT+SAPBR=2,1");
  delay(3000);
  control();
  SWserial.println("AT+HTTPINIT");
  delay(3000);
  control();
  SWserial.println("AT+HTTPPARA=CID,1");
  delay(3000);
  control();
  SWserial.println("AT+HTTPPARA=URL," + url +precent);
  delay(3000);
  control();
  SWserial.println("AT+HTTPPARA=CONTENT,application/x-www-form-urlencoded");
  delay(3000);
  control();
  SWserial.println("AT+HTTPDATA=192,5000");
  delay(3000);
  control();
  SWserial.println("param=TestFromMySim800");
  delay(3000);
  control();
  SWserial.println("AT+HTTPACTION=0");
  delay(3000);
  control();
  SWserial.println("AT+HTTPREAD");
  delay(3000);
  control();
  SWserial.println("AT+HTTPTERM");
  delay(3000);
  control();
  SWserial.println("AT+SAPBR=0,1");
  delay(3000);
}
void gsm_config_gprs() {
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("- CONFIG GPRS -");
  SWserial.println("AT+SAPBR=3,1,Contype,GPRS");
  SWserial.println("AT+SAPBR=3,1,APN," + apn);
  if (apn_u != "") {
    SWserial.println("AT+SAPBR=3,1,USER," + apn_u);
  }
  if (apn_p != "") {
    SWserial.println("AT+SAPBR=3,1,PWD," + apn_p);
  }
}
void SendSMS()
{
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Sending SMS...");               //Show this message on serial monitor
  SWserial.println("AT+CMGF=1\r");                   //Set the module to SMS mode
  delay(100);
  SWserial.println("AT+CMGS=\"+250723009502\"\r");  //Your phone number don't forget to include your country code, example +212123456789"
  delay(500);
  SWserial.println("Balance is low 15%");       //This is the text to send to the phone number, don't make it too long or you have to modify the SoftwareSerial buffer
  delay(500);
  SWserial.println((char)26);// (required according to the datasheet)
  delay(500);
  lcd.setCursor(0,1);
  SWserial.println();
  lcd.print("Text Sent.");
  delay(500);
  var=1;
}
void control(){
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
  } else {
    digitalWrite(pump, LOW);
    pumpState = 0;
  }
  if (liveliters >0.6 && liveliters <=4) {   
    // turn Pump off:
    var=0;
  }
  if (liveliters <=0.6 && var==0) {   
    SendSMS();
  } else {
    }
}
