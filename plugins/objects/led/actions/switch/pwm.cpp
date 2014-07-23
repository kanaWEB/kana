/*
 * pwm.c:
 *	Test of the software PWM driver
 *	
 *
 * Copyright (c) 2012-2013 Gordon Henderson. <projects@drogon.net>
 * Hacked by Sarrailh Rémi 2014
 */

#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <string.h>

#include <wiringPi.h>
#include <softPwm.h>

#define RANGE		100


int main (int argc, char **argv)
{

  int led = atoi(argv[1]);

  int i, j ;
  wiringPiSetup();
  softPwmCreate (led, 0, RANGE) ;

// Bring all up one by one:
 for (j = 0 ; j <= 100 ; ++j)
    {
      softPwmWrite (led, j) ;
      delay (10) ;
    }


// Down fast
  for (i = 100 ; i > 0 ; --i)
  {
      softPwmWrite (led, i) ;
    delay (10) ;
}

}
