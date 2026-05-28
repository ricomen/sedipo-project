#!/bin/bash

in2csv  $1 | csvformat -D ";"  > $2

# use: https://csvkit.readthedocs.io/
# apt-get install python-dev python-pip python-setuptools build-essential
# pip install csvkit


