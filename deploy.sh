#!/bin/sh

rm humanid-spam-filter.zip
mkdir humanid-spam-filter

excludes=("humanid-spam-filter" "deploy.sh" "node_modules" "tests" "bin" "get-translation-strings.js" "package.json" "package-lock.json" "sonar-project.properties" "sonar-project.example.properties")

this_dir=$(pwd)
for entry in $this_dir/*; do
  entry_name=${entry/$this_dir\//''}
  if [[ ! " ${excludes[*]} " =~ " ${entry_name} " ]]; then
    echo including $entry
    cp -R $entry humanid-spam-filter/$entry_name
    # whatever you want to do when array doesn't contain value
  fi
done

zip -r humanid-spam-filter.zip humanid-spam-filter
rm -r humanid-spam-filter
