#!/bin/sh

DIR="/startscripts/updates"

# Query pending updates.
updates=$(/usr/lib/update-notifier/apt-check 2>&1)
if [ $? -ne 0 ]; then
    echo "Querying pending updates failed" > $DIR/updates.txt
    exit $STATUS_UNKNOWN
fi

# Check for pending security updates.
pending=$(echo "$updates" | cut -d ";" -f 2)
echo "$pending" > $DIR/updates.txt

# Check for pending non-security updates.
pending=$(echo "$updates" | cut -d ";" -f 1)
echo "$pending" >> $DIR/updates.txt
