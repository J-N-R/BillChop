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

s = socket.socket()
s.bind(("131.125.80.107", 7500))
s.listen(5)

count = 0

while count < 2:
    conn, address = s.accept()
    count = count + 1
    print(conn.recv(1024).decode())
    conn.send('Hi! -Server'.encode())
    conn.close()

print("Connection Finished")
# accept incoming connections (there will need to be a client.py that accepts cgi and connects to server)

# be able to sum up a client's chosen options
