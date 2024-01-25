#!/bin/bash

# noxon! check Chate repo. then on aws . 
# cd /home/ubuntu/chate_13015 && git pull && pm2 restart ecosystem.config.js && pm2 logs chat_13015
# check (need ~ 1 min) https://apisensorica13015.onout.org/ is up and running

# Change directory to vendor_source
cd vendor_source
rm -rf .next
# Download the website
wget --mirror --convert-links --adjust-extension --page-requisites --no-parent --no-host-directories https://apisensorica13015.onout.org/

# Rename JavaScript files
find . -type f -name '*.js' | while read fname; do
    # Construct new filename by removing the hash pattern
    new_fname=$(echo $fname | sed 's/-[a-z0-9]*\.js/\.js/')
    mv "$fname" "$new_fname"
done

# Update references in HTML, CSS, and JS files
find . -type f \( -name "*.html" -o -name "*.css" -o -name "*.js" \) -exec sed -i 's/-[a-z0-9]*\.js/\.js/g' {} +

# Replace https://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/models with the full URL
find . -type f -exec sed -i 's|https://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/models|https://apisensorica13015.onout.orghttps://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/models|g' {} +

# Replace https://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/chat with the full URL
find . -type f -exec sed -i 's|https://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/chat|https://apisensorica13015.onout.orghttps://apisensorica13015.onout.orghttps://apisensorica13015.onout.org/api/chat|g' {} +