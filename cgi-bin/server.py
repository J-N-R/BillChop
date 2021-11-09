#!/usr/local/bin/python3.9

#
# server.py
#
# Description:
#    Server Program for BillChop.
#    Acts as a server that people's data can connect to.
#    
#    Each user will send what they want to buy here; eggs $3, drink $4, etc
#    and the server will sum it up and print out final results once everyone has set their data.
#
#    All results will be printed in JSON, including errors
#
# Made By:
#   rivejona@kean.edu
#


import easySocket
import socket

print("Server Starting...")

# Setup Connection
s = socket.socket()

# On Kean's IP, use port 7500
s.bind(("localhost", 7500))

# Kill the server if no connections after 2 minutes
s.settimeout(120)

# Max 5 connections at once
s.listen(5)

billCount = 0

try:

  # While we've only accepted less than two connections,
    while billCount < 2:

      # Attempt to connect to client (code after only runs after success)
        conn, address = s.accept()

      # Send sample data, increase billCount
        billCount = billCount + 1
        print(conn.recv(1024).decode())
        conn.send('Hi! -Server'.encode())
        conn.close()

except socket.timeout:
    print("No connections found.")

except KeyboardInterrupt:
    s.close()
    print("Server force closed")

except:
    print("Error connecting!")

print("Connection Finished")

# TODOS
# DONE: accept incoming connections (there will need to be a client.py that accepts cgi and connects to server)

# be able to sum up a client's chosen options
