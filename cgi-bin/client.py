#!/usr/local/bin/python3.9

#
# client.py
#
# Description:
#    Client program for BillChop.
#    Will accept data from a HTML form and send to a running python server.
#
# Made By:
#    rivejona@kean.edu
#

# send information with imported function

import easySocket

print("Client Starting...")

try:

  # Attempt to connect to server (code after only runs after success)
    conn = easySocket.connect_tcp("localhost", 7500)

  # Send sample data
    conn.send('Hi! -client'.encode())
    print(conn.recv(1024).decode())

except ConnectionRefusedError:
    print("No Server Detected!")

except:
    print("Error Connecting! Please try again.")

print("Connection Finished!")
