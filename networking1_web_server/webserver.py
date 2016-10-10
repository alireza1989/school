#!/usr/bin/env python3
import re, os
from socket import *
serverPort = 12000
serverSocket = socket(AF_INET, SOCK_STREAM)
# Reuse local addresses
serverSocket.setsockopt(SOL_SOCKET, SO_REUSEADDR, 1)
serverSocket.bind(('localhost', serverPort))
serverSocket.listen(1)
scriptLocation = os.path.realpath(os.path.join(os.getcwd(), os.path.dirname(__file__)))

def formBinaryResponse(bfSize, bfName):
    # HTTP protocol uses CRLF type endlines
    response = ("HTTP/1.1 200 OK\r\n"
                "Accept-Ranges: bytes\r\n"
                "Keep-Alive: timeout=10, max=100\r\n"
                "Connection: Keep-Alive\r\n"
                # Set lengthm, content-type, disposition to faciliate binary download
                "Content-Length: " + str(bfSize) + "\r\n"
                "Content-Type: application/octet-stream\r\n"
                # HTTP protocol expects two endlines as header termination
                "Content-Disposition: attachment; filename=" + bfName + "\r\n\r\n")
    print(response)
    return response

def form404Response(rf):
    html = ("<center>Error 404: File not found!<br>"
            "You have requested for a non existing file: <b>" + rf + "</b><br><br>"
            "Please try another file</center>")
    response = ("HTTP/1.1 200 OK\r\n"
                "Keep-Alive: timeout=10, max=100\r\n"
                "Content-Length: " + str(len(html)) + "\r\n"
                "Content-Type: text/html\r\n\r\n")
    return response + html

print("THE SERVER IS READY TO RECEIVE")

try:
    while True:
        connectionSocket, addr = serverSocket.accept()
        request = connectionSocket.recv(1024).decode('utf-8')
        if not request: 
            continue
        print(":START REQUEST\n" + request + "\n:END REQUEST")
        # Get the second word of the GET request, then discard the first char
        requestedFile = request.split()[1][1:]
        if not requestedFile or requestedFile == "favicon.ico":
            continue
        #print("\nRequested file " + requestedFile, end  '')
        try:
            # Open file in read-only, binary mode
            binaryFile = open(os.path.join(scriptLocation, requestedFile), 'rb')
            print(" FOUND AT " + scriptLocation + '\n')
            data = binaryFile.read()
            connectionSocket.send(formBinaryResponse(len(data), requestedFile).encode('utf-8'))
            for i in range(0, len(data)):
                connectionSocket.sendall(data[i])
                print("Sending: " + data[i])
            binaryFile.close()
        except IOError:
            print(" NOT FOUND at " + scriptLocation + '\n')
            connectionSocket.send(form404Response(requestedFile).encode('utf-8'))
        finally:
            connectionSocket.close()
except KeyboardInterrupt:
    print("\n^C Detected: Terminating gracefully")
finally:
    print("Socket closed")
    serverSocket.close()
