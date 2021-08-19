#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#define trigger 7
#define echo 8

float time=0,distance=0;
LiquidCrystal_I2C lcd(0x27,20,4);  // set the LCD address to 0x27 for a 16 chars and 2 line display

void setup()
{
  pinMode(trigger,OUTPUT);
  pinMode(echo,INPUT);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0,0);
  lcd.print("Universal Fluid");
  lcd.setCursor(0,1);
  lcd.print("MNGMNT system");
  lcd.setCursor(0,2);
  delay(2000);
  lcd.print(" Ultra sonic");
  lcd.setCursor(0,1);
  lcd.print("Distance Meter");
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
 lcd.clear();
 lcd.print("Distance:");
 lcd.print(distance);
 lcd.print("cm");
 lcd.setCursor(0,1);
 lcd.print("Distance:");
 lcd.print(distance/100);
 lcd.print("m");
 delay(1000);
}
