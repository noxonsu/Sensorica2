#!/bin/bash

# The file containing the CSS
CSS_FILE="static/new.css"
# Temporary file
TMP_FILE=$(mktemp)

# Prefix to add to the classes
PREFIX="sensorica_"

# Use sed to find and replace class names with the prefix
# This looks for .class { or .class, or .class\n and replaces it with .prefixclass
sed -r "s/\.([a-zA-Z_-][a-zA-Z0-9_-]*)([ ,{:.#])/.${PREFIX}\1\2/g" $CSS_FILE > $TMP_FILE

# Replace the original file with the updated one
mv $TMP_FILE $CSS_FILE

# Optional: Remove the temporary file
rm -f $TMP_FILE