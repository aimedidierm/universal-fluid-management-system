/*
trigger -> 7
echo -> 8
SDA -> A4
SCL-> A5
*/
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#define trigger 7
#define echo 8
/*
22cm->100%
tanksize=22/100
*/
float tanksize=0.22,time=0,distance=0,precent=0;

LiquidCrystal_I2C lcd(0x27,20,4);  // set the LCD address to 0x27 for a 16 chars and 2 line display

void setup()
{
  pinMode(trigger,OUTPUT);
  pinMode(echo,INPUT);
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
 lcd.clear();
 lcd.print(precent);
 delay(1000);
}
