#!/usr/local/bin/python3.9

#
# newBill.py
#
# Description:
#   Ran by the user when submitting a new bill
#
#   Creates a code that represents the ID for a bill
#   Tells the server that a new bill has been created
#
#   If no server is detected, start one.
#
# Made By:
#   rivejona@kean.edu
#


import easySocket
import socket
import random

print("New Bill Starting...")

try:

  # Test if server exists
    conn = easySocket.connect_tcp("localhost", 7500)

  # Send random code to the server (a new bill will be created)
    randomNumber = random.randint(0, 9)
    conn.send(str(randomNumber).encode())

  # Print returned data
    print(conn.recv(1024).decode())

# If server doesn't exist, create one
except ConnectionRefusedError:
    print("No Server Detected!")
    print("Creating one now...")
    exec(open("server.py").read())
    quit()

except:
    print("Error Connecting!")

print("Connection Finished!")
