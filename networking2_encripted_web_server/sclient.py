###################################################
##                                               ##
##             Secure client                     ##
##                   							 ##
##                     				             ##
##                                               ##
##                                               ##
###################################################

# **************** Secure Server *****************
# Code used to generate good permutations
'''
print (original_list)
for x in [2,3,5,7,11,13,17,19,23,29]:
	for i in range(40):

	 	original_list[i],original_list[(i + x) % 40] = original_list[(i + x) % 40], original_list[i]

	print (original_list)
'''
# Original list of charaters

original_list = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', ',', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ' ']

# ROTOS.  This rotors are generated as a random permutation
p1 = ['B', 'E', 'A', 'I', 'J', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', ',', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'C', 'D', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'O', ' ']
p2 = ['P', 'Q', 'B', 'E', 'A', 'I', 'J', 'Y', 'Z', '-', ',', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'C', 'D', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'O', 'R', 'S', 'T', 'U', 'V', 'W', 'X', ' ']
p3 = ['A', 'B', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', ',', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'C', 'D', ' ']
p4 = ['Y', 'Z', '-', ',', 'P', 'Q', 'B', 'E', 'A', 'I', 'J', '9', 'C', 'D', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'O', 'R', 'S', 'T', 'U', 'V', 'W', 'X', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', ' ']
p5 = ['Z', '-', ',', 'P', 'Q', 'B', 'E', 'A', 'I', 'J', '9', 'C', 'Y', 'V', 'W', 'X', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', 'D', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'O', 'R', 'S', 'T', 'U', ' ']
p6 = ['E', 'A', 'I', 'J', '9', 'C', 'Y', 'V', 'W', 'X', '.', 'Z', '-', ',', 'P', 'Q', 'B', 'N', 'O', 'R', 'S', 'T', 'U', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', 'D', 'F', 'G', 'H', 'K', 'L', 'M', ' ']
p7 = ['I', 'J', '9', 'C', 'Y', 'V', 'W', 'X', '.', 'Z', '-', ',', 'P', 'Q', 'B', 'N', 'O', 'E', 'A', 'L', 'M', 'R', 'S', 'T', 'U', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', 'D', 'F', 'G', 'H', 'K', ' ']
p8 = ['B', 'E', 'A', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', ',', '.', '?', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'C', 'D', 'F', 'G', 'H', ' ']
p9 = ['E', 'A', 'L', 'M', 'R', 'S', 'I', 'J', '9', 'C', 'Y', 'V', 'W', 'X', '.', 'Z', '-', ',', 'P', 'Q', 'B', 'N', 'O', '3', '4', '5', '6', '7', '8', 'D', 'F', 'G', 'H', 'K', 'T', 'U', '?', '0', '1', '2', ' ']
p10 = ['V', 'W', 'X', '.', 'Z', '-', ',', 'P', 'Q', 'B', 'N', 'O', '3', '4', '5', '6', '7', '8', 'E', 'A', 'L', 'M', 'R', 'S', 'I', 'J', '9', 'C', 'Y', '?', '0', '1', '2', 'D', 'F', 'G', 'H', 'K', 'T', 'U', ' ']

# This function makes a 2d array that are schemes.  each schem consists of tow rotor
def makeScheme (perm1, perm2):
    scheme = list ([])
    scheme.append (perm1)
    scheme.append (perm2)
    return scheme

# making schems 1 to 5
sch1 = makeScheme (p1, p2)
sch2 = makeScheme (p3, p4)
sch3 = makeScheme (p5, p6)
sch4 = makeScheme (p7, p8)
sch5 = makeScheme (p9, p10)

# Shit and reverse shift functions that are used for shifting a rotor by n
def shift(l, n):
    n = n % len(l)
    head = l[:-n]
    l[:-n] = []
    l.extend(head)
    return l

def rshift(l,n):
    n = n % len(l)
    head = l[:n]
    l[:n] = []
    l.extend(head)
    return l

########################################################################################
# Encription function which is using rotor (ennigma machine) algorithm to encode a text
def encript (message, scheme):
    message = list (message)
    for i,val in enumerate(message):
        message[i] = scheme[0][original_list.index(val)]
        message[i] = scheme[1][scheme[0].index(message[i])]
        shift (scheme[1],1)
        if i != 0 and (i % 41) == 0:
            shift(scheme[0],1)
    return "".join(message)


# Encription function which is using rotor (ennigma machine) algorithm to encode a text
def decript (message, scheme):

    message = list (message)
    x = int (len(message) / 41)
    #rshift (scheme[1], len(message))
    #rshift (scheme[0], x)
    for i , val in enumerate (message):
        message[i] = scheme[0][scheme[1].index(val)]
        message[i] = original_list[scheme[0].index(message[i])]
        shift(scheme[1],1)
        if i !=0 and (i % 41) == 0:
            shift(scheme[0],1)
    return "".join(message)


# diffie-Hellman algorithm

# Generating Y

def dhy (x,n,q):
    y = (n ** x) % q
    return y

# Generating shared key K
def dhk (y,x,q):
    k = (y ** x) % q
    return k

########################################################################################


# Sample message from client
clientmessage = "Hi Server.  Thank you for secure connection.  Please transfer some money to my bank account.  Is there anything that you want me to do? This message is more than 41 character".upper()

# CLIENT CODE
import socket               # Import socket module

s = socket.socket()         # Create a socket object
host = socket.gethostname() # Get local machine name
port = 12345                # Reserve a port for your service.

s.connect((host, port))

# Diffie Hellman steps to generate a shared key

# Client receives n and q from server
pubnum = s.recv(1024)

# n = pubnum[0:2]
# q = pubnum[2:4]
n = '83'
q = '97'

intn = int (n)
intq = int (q)
xclient = 31

# Public key of client
yclient = str (dhy (xclient, intn, intq))

s.send(yclient)

yserver = int (s.recv(1024))

# Generating shared key K
k = dhk (yserver, xclient, intq)
print 'The generated shared key is: ', k

schemNum = k % 5
print 'The sheme number extracted from shared key is: ', schemNum

# Check which scheme is being choosen.  The Server sends one message after encription and clien replies by one encripted message.  The decription happens in destination

if schemNum == 0 :
    serverMessage = s.recv(1024)
    print 'Message received from server encoded: ' , serverMessage
    serverMessage = decript (serverMessage,sch1)
    print 'Message received from server decoded: ' , serverMessage

    print 'Message to send to server before encription: ' , "".join(clientmessage)
    encodedMessage = encript(clientmessage, sch1)
    print 'Message to send to server encoded: ' , "".join(encodedMessage)
    s.send(encodedMessage)
    s.close
elif schemNum == 1 :
    serverMessage = s.recv(1024)
    print 'Message received from server encoded: ' , serverMessage
    serverMessage = decript (serverMessage,sch2)
    print 'Message received from server decoded: ' , serverMessage

    print 'Message to send to server before encription: ' , "".join(clientmessage)
    encodedMessage = encript(clientmessage, sch2)
    print 'Message to send to server encoded: ' , "".join(encodedMessage)
    s.send(encodedMessage)
    s.close                     # Close the socket when done
elif schemNum == 2:
    serverMessage = s.recv(1024)
    print 'Message received from server encoded: ' , serverMessage
    serverMessage = decript (serverMessage,sch3)
    print 'Message received from server decoded: ' , serverMessage

    print 'Message to send to server before encription: ' , "".join(clientmessage)
    encodedMessage = encript(clientmessage, sch3)
    print 'Message to send to server encoded: ' , "".join(encodedMessage)
    s.send(encodedMessage)
    s.close                     # Close the socket when done
elif schemNum == 3:
    serverMessage = s.recv(1024)
    print 'Message received from server encoded: ' , serverMessage
    serverMessage = decript (serverMessage,sch4)
    print 'Message received from server decoded: ' , serverMessage

    print 'Message to send to server before encription: ' , "".join(clientmessage)
    encodedMessage = encript(clientmessage, sch4)
    print 'Message to send to server encoded: ' , "".join(encodedMessage)
    s.send(encodedMessage)
    s.close                     # Close the socket when done
elif schemNum == 4:
    serverMessage = s.recv(1024)
    print 'Message received from server encoded: ' , serverMessage
    serverMessage = decript (serverMessage,sch5)
    print 'Message received from server decoded: ' , serverMessage

    print 'Message to send to server before encription: ' , "".join(clientmessage)
    encodedMessage = encript(clientmessage, sch5)
    print 'Message to send to server encoded: ' , "".join(encodedMessage)
    s.send(encodedMessage)
    s.close                     # Close the socket when done
