#include <SoftwareSerial.h>

String apn = "www.tigo.rw";                    //APN
String apn_u = "";                     //APN-Username
String apn_p = "";                     //APN-Password
String url = "http://147.182.250.105/universal-fluid-management-system/gsmdata.php?per=";  //URL of Server
int per = 10;
SoftwareSerial SWserial(10, 11); // RX, TX

void setup()
{
  Serial.begin(115200);
  Serial.println("SIM800 AT CMD Test");
  SWserial.begin(9600);
  delay(1000);
  while (SWserial.available()) {
    Serial.write(SWserial.read());
  }
  delay(500);
  gsm_config_gprs();
}

void loop() {
  gsm_http_post("param=TestFromMySim800");
  delay(30000);
}

void gsm_http_post( String postdata) {
  Serial.println(" --- Start GPRS & HTTP --- ");
  gsm_send_serial("AT+SAPBR=1,1");
  gsm_send_serial("AT+SAPBR=2,1");
  gsm_send_serial("AT+HTTPINIT");
  gsm_send_serial("AT+HTTPPARA=CID,1");
  gsm_send_serial("AT+HTTPPARA=URL," + url +per);
  gsm_send_serial("AT+HTTPPARA=CONTENT,application/x-www-form-urlencoded");
  gsm_send_serial("AT+HTTPDATA=192,5000");
  gsm_send_serial(postdata);
  gsm_send_serial("AT+HTTPACTION=0");
  gsm_send_serial("AT+HTTPREAD");
  gsm_send_serial("AT+HTTPTERM");
  gsm_send_serial("AT+SAPBR=0,1");
}

void gsm_config_gprs() {
  Serial.println(" --- CONFIG GPRS --- ");
  gsm_send_serial("AT+SAPBR=3,1,Contype,GPRS");
  gsm_send_serial("AT+SAPBR=3,1,APN," + apn);
  if (apn_u != "") {
    gsm_send_serial("AT+SAPBR=3,1,USER," + apn_u);
  }
  if (apn_p != "") {
    gsm_send_serial("AT+SAPBR=3,1,PWD," + apn_p);
  }
}

void gsm_send_serial(String command) {
  Serial.println("Send ->: " + command);
  SWserial.println(command);
  long wtimer = millis();
  while (wtimer + 3000 > millis()) {
    while (SWserial.available()) {
      Serial.write(SWserial.read());
    }
  }
  Serial.println();
}
