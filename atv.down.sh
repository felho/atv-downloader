#!/bin/bash

BASE_DIR=$(cd $(dirname $0); pwd -P)
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

	rtmpdump -W "http://www.atv.hu/js/flowplayer/flowplayer-3.2.15.swf" --tcUrl="rtmp://$SERVERIP/mediacache/_definst_" --pageUrl="http://www.atv.hu/videok" -r "rtmp://$SERVERIP/mediacache/_definst_" -y "mp4:atv/$INPUT.mp4" --live -o "$OUTPUT.mp4"
	EXIT_CODE=$?

	if [ $EXIT_CODE -ne 0 ]
  then
    echo "$* failed with exit code $EXIT_CODE - retry"
		#The reason why we retry once is that in majority of the cases the issue behind a failed download is closing the lid of the laptop, switching wifi etc.
		rtmpdump -W "http://www.atv.hu/js/flowplayer/flowplayer-3.2.15.swf" --tcUrl="rtmp://$SERVERIP/mediacache/_definst_" --pageUrl="http://www.atv.hu/videok" -r "rtmp://$SERVERIP/mediacache/_definst_" -y "mp4:atv/$INPUT.mp4" --live -o "$OUTPUT.mp4"
		EXIT_CODE=$?
		if [ $EXIT_CODE -ne 0 ]
	  then
	    echo "$* failed with exit code $EXIT_CODE - go for next item"
	  else
			echo "$TABLE - $EXIT_CODE" >> $FINISHED_LIST
	  fi
  else
		echo "$TABLE - $EXIT_CODE" >> $FINISHED_LIST
  fi
done < $INPUT_LIST
