#!/usr/local/bin/python3.9

#
# client.py
#
# Description:
#    Client program for BillChop.
#    Will accept data from a HTML form and send to a running python server.
#
#    If a server cannot be found, create one.
#
# Made By:
#   rivejona@kean.edu
#

# import necessary stuff

# maybe a couple of if statments here

# send information with imported function

import easySocket

print("Client Starting...")

try:
    conn = easySocket.connect_tcp("131.125.80.107", 7500)
    conn.send('Hi! -client'.encode())
    print(conn.recv(1024).decode())
    print("Connection finished!")
except:
    print("Error Connecting! Please try again. Error: ")
