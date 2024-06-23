# smart-attendance-by-RFID


This project is about tracking and managing student attendances using IoT. We have a PHP server with PHPMyAdmin database and a NodeMCU ESP8266 (WiFi enabled) compatible board with RFID sensor MFRC522 and tags/cards. The idea is to give students the RFID tags/cards (which can be of any form like identity card) and they can use it before entering their class to register their attendances which can be tracked and managed by a beautiful & user friendly frontend application.

> ## How to spin up the server and the web application 🚀
> - Turn On the Apache & MySQL from the XAMPP server at the beginning.
> - First make sure to create a database in PHPMyAdmin named ```SmartAttendanceByRFID```.
> - Then import the SQL file in PHPMyAdmin to constuct the database. I have used InnoDB here.
> - IFF you're using Arduino IDE first time with ```NodeMCU ESP8266```, then download the ```MFRC522``` Module from the Library section of the IDE & go to ```File -> Preferences -> Additional Boards Manager URLs``` and paste the below given link------
> - ```
>   http://arduino.esp8266.com/stable/package_esp8266com_index.json
>   ```
> - Make the pin diagram as mentioned in the ```.ino``` codes (you can use your's too...at that case change at the ```#define``` portions mentioned in every code.  
> ```
> #define RST_PIN   D3   // Define the pin connected to the RST (reset) pin on the MFRC522
> #define SS_PIN    D2   // Define the pin connected to the SDA(SS) pin on the MFRC522
> #define SCK_PIN   D5   // Define the pin connected to the SCK pin on the MFRC522
> #define MOSI_PIN  D7   // Define the pin connected to the MOSI pin on the MFRC522
> #define MISO_PIN  D6   // Define the pin connected to the MISO pin on the MFRC522  
> #define BUZZER    D8   // Define the pin connected to the Buzzer
> ```
> Write all the cards as per as you want......
> Then make the changes in the <a href="https://github.com/arkyaray2002/smart-attendance-by-RFID/blob/main/Attendance/Attendance.ino">```Attendance.ino```</a> , <a href="https://github.com/arkyaray2002/smart-attendance-by-RFID/blob/main/INSERT_STUDENT/INSERT_STUDENT.ino">```INSERT_STUDENT.ino```</a> , <a href="https://github.com/arkyaray2002/smart-attendance-by-RFID/blob/main/INSERT_TEACHER/INSERT_TEACHER.ino">```INSERT_TEACHER.ino```</a> & Run it in Arduino IDE
> ```
> const char* ssid = "Your_WiFi_Name";     // Change it with your WiFi SSID (NOTE: ESP8266 supports 2.4GHz WiFi only)
> const char* password = "Your_WiFi_Password"; // Change it with your WiFi password
> const char* serverAddress = "http://192.168.29.108/SmartAttendanceByRFID/dbwrite.php"; // Change the IP Adress with your IPV4 address
> ```
> - Now open the ```index.html``` page in Localhost Server
> - Enter the Username = ```admin```, Password = ```password```...... & Enjoy 🎉🎉🎉

<center>
<img src="./images/RFID PROJECT - BLUEPRINT.png" alt="RFID PROJECT - BLUEPRINT.png" width="496" height="350">
<img src="./images/RFID PROJECT - TOPOLOGY.png" alt="RFID PROJECT - TOPOLOGY.png" width="496" height="350">
</center>
