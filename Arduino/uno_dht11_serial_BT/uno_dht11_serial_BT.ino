#include "DHT.h"
#define dhtPin 8      //讀取DHT11 Data
#define dhtType DHT11 //選用DHT11   
#include <SoftwareSerial.h>  //引用程式庫
#include <SPI.h>
#include <Wire.h>

SoftwareSerial btSerial(10, 11);  //RX、TX

SoftwareSerial s(6,7);


DHT dht(dhtPin, dhtType); // Initialize DHT sensor

void setup() {
  s.begin(9600);
  btSerial.begin(9600);  //HC-06 current bound rate (default 9600)
  Serial.begin(9600);
  dht.begin();//啟動DHT
}

void loop() {
  
  float h = dht.readHumidity();//讀取濕度
  float t = dht.readTemperature();//讀取攝氏溫度

  if (isnan(h) || isnan(t)) {
    Serial.println("Can't Read DHT11");
    s.listen();
    s.println("Can't Read DHT11");
    delay(1000);
    btSerial.listen();
    btSerial.println("Can't Read DHT11");
    delay(1000);
    return;
  }
  Serial.print("濕度: ");
  Serial.print(h);
  Serial.print("%\t");
  Serial.print("攝氏溫度: ");
  Serial.print(t);
  Serial.print("*C\n");
  s.listen();
  s.print(String(h));
  s.println(String(t));
  delay(1000);
  btSerial.listen();
  btSerial.print("H:");
  btSerial.print(h);
  btSerial.print("%;");
  btSerial.print("T:");
  btSerial.print(t);
  btSerial.println("*C");
  delay(2000);
}
