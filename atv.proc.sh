#!/bin/bash

BASE_DIR=$(cd $(dirname $0); pwd -P)
INPUT_LIST="$BASE_DIR/0.down.txt"
FINISHED_LIST="$BASE_DIR/0.proc.txt"
LOG="$BASE_DIR/0.proc.log.txt"

while read DATA; do
	if grep --quiet "$DATA" $FINISHED_LIST; then
		echo "done $DATA"
		continue
	fi

	echo "" >> $LOG
	echo "$DATA" >> $LOG

	DAY=`echo $DATA|awk -F__ '{print $2}'|sed -E 's/([0-9]+).*/\1/'`
	INPUT_FILE=`echo $DATA|awk -F__ '{print $2}'|sed -E 's/[0-9]+_(.*)/\1/'`

	INPUT_MP4="$BASE_DIR/$DAY/$INPUT_FILE.mp4"
	OUTPUT_MP4="$BASE_DIR/$DAY/0mp4/$INPUT_FILE.mp4"
	OUTPUT_MP3="$BASE_DIR/$DAY/0mp3/$INPUT_FILE.mp3"

	mkdir -p "$BASE_DIR/$DAY/0mp4"
	mkdir -p "$BASE_DIR/$DAY/0mp3"

	echo "HandBrakeCLI -Z iPod -i $INPUT_MP4 -o $OUTPUT_MP4" >> $LOG
	HandBrakeCLI -Z iPod -i $INPUT_MP4 -o $OUTPUT_MP4
	STATUS="$?"
	echo "$?" >> $LOG

	echo "ffmpeg -i $OUTPUT_MP4 -vn -ac 2 -ar 44100 -ab 128k -f mp3 -y $OUTPUT_MP3" >> $LOG
	ffmpeg -i $OUTPUT_MP4 -vn -ac 2 -ar 44100 -ab 128k -f mp3 -y $OUTPUT_MP3
	STATUS="$STATUS - $?"
	echo "$?" >> $LOG

	echo "$DATA - $STATUS" >> $FINISHED_LIST

	exit
done < $INPUT_LIST
