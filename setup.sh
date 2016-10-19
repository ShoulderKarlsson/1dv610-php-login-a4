#!/bin/sh

echo "Creating cookies.json"
touch db/cookies.json
echo "[]" | tee db/cookies.json > /dev/null

echo "Creating accounts.json"
touch db/accounts.json
echo "[]" | tee db/accounts.json > /dev/null

echo "Done - Ready to run!"