#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

LiquidCrystal_I2C lcd(0x27,20,4);  // set the LCD address to 0x27 for a 16 chars and 2 line display


#define RST_PIN   D1   // Define the pin connected to the RST (reset) pin on the MFRC522
#define SS_PIN    D2   // Define the pin connected to the SDA(SS) pin on the MFRC522
#define SCK_PIN   D5   // Define the pin connected to the SCK pin on the MFRC522
#define MOSI_PIN  D7   // Define the pin connected to the MOSI pin on the MFRC522
#define MISO_PIN  D6   // Define the pin connected to the MISO pin on the MFRC522
#define BUZZER    D8   // Define the pin connected to the Buzzer

MFRC522 mfrc522(SS_PIN, RST_PIN);  // Instance of the class
MFRC522::MIFARE_Key key;  

const char* ssid = "RAYs";     // Change it with your WiFi SSID
const char* password = "1234567890"; // Change it with your WiFi password
const char* serverAddress = "http://192.168.29.108/SmartAttendanceByRFID/insert_student.php"; // Change it with your server address

String lastCardUID = ""; // Variable to store the last scanned RFID UID

WiFiClient client; // Define WiFiClient object globally

void setup() {
  // Initialize I2C with custom pins
  Wire.begin(0, 2); // SDA to GPIO 0 (D3), SCL to GPIO 2 (D4)

  lcd.init();                      // initialize the lcd 
  lcd.init();
  lcd.backlight();

  Serial.begin(9600);
  SPI.begin();     // Initialize SPI bus
  mfrc522.PCD_Init();   // Initialize MFRC522
  pinMode(BUZZER, OUTPUT); // Set BUZZER as OUTPUT
  delay(100);
  connectToWiFi(); // Connect to WiFi
}

void loop() {
  if (mfrc522.PICC_IsNewCardPresent()) {
    if (mfrc522.PICC_ReadCardSerial()) {
      String cardUID = getCardUID();
      if (cardUID != lastCardUID) {
        lastCardUID = cardUID;
        // Now that card is scanned, take inputs
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Card Scanned");
        lcd.setCursor(0, 1);
        lcd.print("UID : ");
        lcd.print(cardUID);
        buzz(BUZZER, 50);
        delay(100);
        buzz(BUZZER, 50);
        String studentName = getStudentName();
        int studentSemester = getStudentSemester();
        sendStudentInfoToServer(studentName, studentSemester, cardUID);
        delay(1000); // Delay to avoid multiple readings
      }
    }
  }
}


String getCardUID() {
  String cardUID = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    cardUID.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : ""));
    cardUID.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  return cardUID;
}

String getStudentName() {
  Serial.println("Enter student name:");
  while (!Serial.available()) {
    // Wait for user input
  }
  String name = Serial.readStringUntil('\n');
  return name;
}

int getStudentSemester() {
  Serial.println("Enter student semester (1-6):");
  while (!Serial.available()) {
    // Wait for user input
  }
  int semester = Serial.parseInt();
  return semester;
}

void connectToWiFi() {
  Serial.println();
  lcd.setCursor(0, 0);
  lcd.print("Connecting ");
  lcd.print(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    lcd.setCursor(0, 1);
    lcd.print(".");
  }
  
  // Clear the screen
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("");
  lcd.print("WiFi connected");
  buzz(BUZZER, 50);
  delay(50);
  buzz(BUZZER, 50);
  delay(2000);

  // Clear the screen
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("IP address: ");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());
  delay(3000);

  // Clear the screen
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("Preparing RFID");
  lcd.setCursor(0, 1);
  lcd.print("to start scaning");
  delay(2000); // Wait for 1 second
  
  // Clear the screen
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("Preparing RFID");

  // Display countdown notification
  lcd.setCursor(0, 1);
  lcd.print("Wait... 3");
  buzz(BUZZER, 50);
  delay(1000); // Wait for 1 second
  lcd.setCursor(0, 1);
  lcd.print("Wait... 2");
  buzz(BUZZER, 50);
  delay(1000); // Wait for 1 second
  lcd.setCursor(0, 1);
  lcd.print("Wait... 1");
  buzz(BUZZER, 50);
  delay(1000); // Wait for 1 second 

  // Clear the screen
  lcd.clear();
  
  // Display countdown notification
  lcd.setCursor(0, 0);
  lcd.print("Scan now...");
  buzz(BUZZER, 500);
}

void sendUIDToServer(String cardUID) {
  Serial.println("Sending RFID UID to server: " + cardUID);
  buzz(BUZZER, 500);
  delay(500);
  HTTPClient http;
  http.begin(client, serverAddress); // Use the correct begin overload
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST("student_rfid=" + cardUID);
  if (httpResponseCode == 200) {
    Serial.println("RFID UID sent successfully");
    buzz(BUZZER, 50);
    delay(100);
    buzz(BUZZER, 50);
    delay(100);
  } else {
    Serial.print("Error sending RFID UID: ");
    Serial.println(httpResponseCode);
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(100);
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(100);
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(100);
  }
  http.end();
}

void sendStudentInfoToServer(String studentName, int studentSemester, String cardUID) {
  Serial.println("Sending student info to server: ");
  Serial.println("Student Name: " + studentName);
  Serial.println("Student Semester: " + String(studentSemester));

  // Clear the screen
  lcd.clear();
  Serial.println("Card UID: " + cardUID);
  lcd.setCursor(0, 0);
  lcd.print(studentName);
  lcd.setCursor(0, 1);
  lcd.print(studentSemester);
  delay(1500);
  lcd.setCursor(0, 1);
  lcd.print(cardUID);
  
  buzz(BUZZER, 500);
  delay(500);
  HTTPClient http;
  http.begin(client, serverAddress); // Use the correct begin overload
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String postData = "student_name=" + studentName + "&student_sem=" + String(studentSemester) + "&student_rfid=" + cardUID;
  int httpResponseCode = http.POST(postData);
  if (httpResponseCode == 200) {
    Serial.println("Student info sent successfully");

    // Clear the screen
    lcd.clear();

    lcd.setCursor(0, 0);
    lcd.print("INSERTION");
    lcd.setCursor(0, 1);
    lcd.print("SUCCESSFUL");
    buzz(BUZZER, 50);
    delay(100);
    buzz(BUZZER, 50);
    delay(100);
  } else {
    Serial.print("Error sending student info: ");
    Serial.println(httpResponseCode);

    // Clear the screen
    lcd.clear();
    
    lcd.setCursor(0, 0);
    lcd.print("INSERTION");
    lcd.setCursor(0, 1);
    lcd.print("Error ");
    lcd.print(httpResponseCode);
    
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(100);
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(100);
    buzz(BUZZER, 1000);
    delay(100);
    buzz(BUZZER, 100);
    delay(2000);
  }
  http.end();
  

    // Clear the screen
    lcd.clear();
  
  // Display restart notification
  lcd.setCursor(0, 0);
  lcd.print("Press RST button");
  lcd.setCursor(0, 1);
  lcd.print("to insert next..");
}

void buzz(int targetPin, long duration) {
  digitalWrite(targetPin, HIGH);
  delay(duration);
  digitalWrite(targetPin, LOW);
}