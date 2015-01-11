#!/bin/bash

BASE_DIR="/Users/felho/Series/0atv"
INPUT_LIST="$BASE_DIR/0.txt"
FINISHED_LIST="$BASE_DIR/0.down.txt"

while read TABLE; do
	if grep --quiet "$TABLE" $FINISHED_LIST; then
		echo "done $TABLE"
		continue
	fi

	INPUT=`echo $TABLE|awk -F__ '{print $1}'`
	DAY=`echo $TABLE|awk -F__ '{print $2}'|sed -E 's/([0-9]+).*/\1/'`
	SERVERIP=`echo $TABLE|awk -F__ '{print $3}'`
	OUTPUT_FILE=`echo $TABLE|awk -F__ '{print $2}'|sed -E 's/[0-9]+_(.*)/\1/'`

	mkdir -p "$BASE_DIR/$DAY"
	OUTPUT="$BASE_DIR/$DAY/$OUTPUT_FILE"

	rtmpdump -W "http://www.atv.hu/js/flowplayer/flowplayer-3.2.15.swf" --tcUrl="rtmp://$SERVERIP/mediacache/_definst_" --pageUrl="http://www.atv.hu/videok" -r "rtmp://$SERVERIP/mediacache/_definst_" -y "mp4:atv/$INPUT.mp4" -o "$OUTPUT.mp4"

	echo "$TABLE - $?" >> $FINISHED_LIST
done < $INPUT_LIST
