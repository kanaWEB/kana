#!/bin/bash
echo "sending code $* to GPIO 3"
irsend send_once $*