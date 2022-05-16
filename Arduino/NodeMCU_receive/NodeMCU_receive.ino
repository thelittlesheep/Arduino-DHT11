#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
const char* ssid = "AndroidAP";  // Your SSID
const char* password = "Hm3416077";  // Your Password
const char* host = "192.168.43.64";  // Server IP

SoftwareSerial fromNano(2, 14); //Rx, Tx

WiFiClient client;

void setup()
{
  Serial.begin(9600);
  fromNano.begin(9600);

    //connect to wifi
  Serial.print("Connecting to ");
  Serial.println(ssid);

  //initializing wifi
  WiFi.begin(ssid, password);
  while (WiFi.status()!=WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  // Print out your wifi status on SerialMonitor
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print(WiFi.localIP());
  Serial.println(" connecting...");

  //connect to host
  Serial.print("Connecting to ");
  Serial.println(host);
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("Connection failed!");
    return;
  }
}

void loop()
{

  if (fromNano.available())
  {
    String rawdata = fromNano.readString();
    String h="";
    String t="";
    for (int i=0;i<rawdata.length()-7;i++){
      h += rawdata[i];
    }
    for (int i=5;i<rawdata.length()-2;i++){
      t += rawdata[i];
    }
    Serial.println(h);
    Serial.println(t);
    if (client.connect(host, 80)) {
      client.print("GET /insertTHData.php?");
      client.print("temperature=");
      client.print(t);
      client.print("&humidity=");
      client.print(h);
      client.println(" HTTP/1.1"); // Part of the GET request
      client.println("Host: 192.168.43.64"); //this Host field should be inserted your server ip address
      client.println("Connection: close");
      client.println();  // Empty line
      client.stop();  // Closing connection to server
      Serial.println("* Data inserted successfully!\n");
    } 
    else {
      // If Arduino can't connect to the server (your computer or web page)
      Serial.println("--> Connection failed!\n");
    }
    delay(1000);
  }
}
