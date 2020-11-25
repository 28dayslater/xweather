#!/bin/bash

FILE=install.ready

if [ -f "$FILE" ]; then
    php artisan serve --host=0.0.0.0 --port=8000
else 
    echo "Installation of Laravel still in progress. Wait a few minutes (3-5 minutes), and retry before testing."
fi