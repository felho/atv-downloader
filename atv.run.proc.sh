#!/bin/bash

BASE_DIR=$(cd $(dirname $0); pwd -P)

while true; do sh "$BASE_DIR/atv.proc.sh"; sleep 1; done
