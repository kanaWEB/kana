#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>

int main (int argc, char **argv)
{
	int led = atoi(argv[1]);
	int state = atoi(argv[2]);

	wiringPiSetup();

	pinMode(led,OUTPUT);
	digitalWrite(led,state);
}
