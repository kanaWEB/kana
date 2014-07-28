#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>

int main (int argc, char **argv)
{
	int led = atoi(argv[1]);
	int speed = atoi(argv[2]);
	int times = atoi(argv[3]);
	int i,j;

	wiringPiSetup();

	pinMode(led,OUTPUT);
	for(j=1;j <= times;j++){
	digitalWrite(led,HIGH);
	delay(speed);
	digitalWrite(led,LOW);
	delay(speed);
	}
}
