#!/usr/local/bin/python3.8

#
# imageReader.py
#
# Description:
#    CGI Program for BillChop.
#    Takes in an image from a HTML form
#    and sends it to an API for reading.
# 
#    All results will be printed in JSON, including errors
#
# Made By:
#   whoever@kean.edu + rivejona@kean.edu
#

import os
import cgi
import cgitb
import stat


# Enable cgitb and set form
cgitb.enable()
form = cgi.FieldStorage()

print('Content-Type: application/json\n')

try:
    if 'image' not in form:
        raise Exception('"Error":"No Image Detected"')

  # Initialize important variables
    image = form['image']

    basePath = 'upload/'

    imageTarget = basePath + image.filename

    fileExtension = os.path.splitext(image.filename)[1]

  # if image is not JPG, PNG, or some other type
    if fileExtension != '.csv':
        raise Exception('"Error":"Wrong Image Type"')
        

  # Upload Files
  # Only upload if conditions above are valid
    with open(imageTarget, 'wb') as targetFile:
        targetFile.write(image.file.read())
        os.chmod(imageTarget, stat.S_IRWXU | stat.S_IROTH | stat.S_IXOTH) #chmod 705

  # SEND THE FILE TO THE API
  # Import something that can send out URL request
  # probably have to pass a link to the file, you can use http://yoda.kean.edu/~rivejona/BilChop/ + imageTarget
  # print(json) and end program
    
except Exception as Err:
    print(Err)
